<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookingStoreRequest;
use App\Models\PanditProfile;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function create(PanditProfile $pandit)
    {
        $pandit->load('user', 'services');
        return view('booking.create', compact('pandit'));
    }

    public function store(BookingStoreRequest $request, PanditProfile $pandit)
    {
        $validated = $request->validated();

        // Get Service info
        $service = $pandit->services()->where('service_id', $validated['service_id'])->first();
        abort_unless($service, 422, 'Selected service is not available for this Pandit.');
        $totalAmount = $service->pivot->custom_price ?? $service->base_price ?? 1100;
        $durationHours = $service->duration_hours ?? 2;

        $bookingStart = Carbon::parse($validated['booking_time']);
        $bookingEnd = $bookingStart->copy()->addHours($durationHours);

        // ─── DOUBLE BOOKING PREVENTION ─────────────────────
        // Check blocked dates
        $isBlocked = $pandit->blockedDates()->where('blocked_date', $validated['booking_date'])->exists();
        if ($isBlocked) {
            return back()->withErrors(['booking_date' => 'This date is blocked by the Pandit.'])->withInput();
        }

        // Check day availability
        $dayOfWeek = Carbon::parse($validated['booking_date'])->dayOfWeek;
        $dayAvailability = $pandit->availabilities()->where('day_of_week', $dayOfWeek)->where('is_available', true)->first();
        if ($dayAvailability) {
            $availStart = Carbon::parse($dayAvailability->start_time);
            $availEnd = Carbon::parse($dayAvailability->end_time);
            if ($bookingStart->lt($availStart) || $bookingEnd->gt($availEnd)) {
                return back()->withErrors(['booking_time' => 'Selected time is outside the Pandit\'s available hours (' . $availStart->format('h:i A') . ' – ' . $availEnd->format('h:i A') . ').'])->withInput();
            }
        }

        // Overlap check with DB locking to prevent race conditions
        $hasConflict = DB::transaction(function () use ($pandit, $validated, $bookingStart, $bookingEnd) {
            return Booking::where('pandit_profile_id', $pandit->id)
                ->where('booking_date', $validated['booking_date'])
                ->whereIn('status', ['pending', 'confirmed'])
                ->lockForUpdate()
                ->get()
                ->contains(function ($existing) use ($bookingStart, $bookingEnd) {
                    $exStart = Carbon::parse($existing->booking_time);
                    $exEnd = $existing->booking_end_time ? Carbon::parse($existing->booking_end_time) : $exStart->copy()->addHours($existing->duration_hours ?? 2);
                    return $bookingStart->lt($exEnd) && $bookingEnd->gt($exStart);
                });
        });

        if ($hasConflict) {
            return back()->withErrors(['booking_time' => 'This time slot overlaps with an existing booking. Please choose a different time.'])->withInput();
        }

        $booking = Booking::create([
            'user_id' => Auth::id(),
            'pandit_profile_id' => $pandit->id,
            'service_id' => $validated['service_id'],
            'booking_date' => $validated['booking_date'],
            'booking_time' => $validated['booking_time'],
            'booking_end_time' => $bookingEnd->format('H:i:s'),
            'duration_hours' => $durationHours,
            'address' => $validated['address'],
            'status' => 'pending',
            'total_amount' => $totalAmount,
            'payment_status' => 'pending',
        ]);

        return redirect()->route('payment.checkout', ['booking' => $booking->id]);
    }
}
