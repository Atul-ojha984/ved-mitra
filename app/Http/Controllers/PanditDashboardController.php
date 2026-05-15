<?php

namespace App\Http\Controllers;

use App\Http\Requests\BlockDateRequest;
use App\Http\Requests\GetSlotsRequest;
use App\Http\Requests\SaveAvailabilityRequest;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Availability;
use App\Models\BlockedDate;
use App\Models\Earning;
use Carbon\Carbon;

class PanditDashboardController extends Controller
{
    private function profile()
    {
        return auth()->user()->panditProfile;
    }

    // ─── Dashboard Overview ────────────────────────────
    public function dashboard()
    {
        $p = $this->profile();

        $totalBookings = $p->bookings()->count();
        $completedBookings = $p->bookings()->where('status', 'completed')->count();
        $todayBookings = $p->bookings()->whereDate('booking_date', today())->whereIn('status', ['pending', 'confirmed'])->orderBy('booking_time')->get();
        $upcomingBookings = $p->bookings()->where('booking_date', '>=', today())->whereIn('status', ['pending', 'confirmed'])->orderBy('booking_date')->orderBy('booking_time')->take(5)->get();
        $totalEarnings = $p->earnings()->where('status', '!=', 'pending')->sum('net_amount');
        $monthEarnings = $p->earnings()->where('status', '!=', 'pending')->whereMonth('created_at', now()->month)->sum('net_amount');
        $avgRating = $p->reviews()->avg('rating') ?? 0;
        $reviewCount = $p->reviews()->count();
        $pendingBookings = $p->bookings()->where('status', 'pending')->count();

        // Chart data — last 7 days
        $chartLabels = [];
        $chartEarnings = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $chartLabels[] = $date->format('d M');
            $chartEarnings[] = $p->earnings()->where('status', '!=', 'pending')->whereDate('created_at', $date)->sum('net_amount');
        }

        return view('pandit.dashboard', compact(
            'p', 'totalBookings', 'completedBookings', 'todayBookings', 'upcomingBookings',
            'totalEarnings', 'monthEarnings', 'avgRating', 'reviewCount', 'pendingBookings',
            'chartLabels', 'chartEarnings'
        ));
    }

    // ─── Booking Management ────────────────────────────
    public function bookings(Request $request)
    {
        $p = $this->profile();
        $query = $p->bookings()->with(['user', 'service']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $bookings = $query->latest()->paginate(15)->appends($request->query());
        $counts = [
            'all' => $p->bookings()->count(),
            'pending' => $p->bookings()->where('status', 'pending')->count(),
            'confirmed' => $p->bookings()->where('status', 'confirmed')->count(),
            'completed' => $p->bookings()->where('status', 'completed')->count(),
        ];
        return view('pandit.bookings', compact('bookings', 'counts'));
    }

    public function acceptBooking(Booking $booking)
    {
        if ($booking->pandit_profile_id !== $this->profile()->id) abort(403);
        $booking->update(['status' => 'confirmed']);
        return back()->with('success', 'Booking accepted!');
    }

    public function rejectBooking(Booking $booking)
    {
        if ($booking->pandit_profile_id !== $this->profile()->id) abort(403);
        $booking->update(['status' => 'cancelled']);
        return back()->with('success', 'Booking rejected.');
    }

    public function completeBooking(Booking $booking)
    {
        if ($booking->pandit_profile_id !== $this->profile()->id) abort(403);
        $booking->update(['status' => 'completed']);

        // Create earning record with commission
        if ($booking->payment_status === 'paid' && !$booking->earning) {
            $commissionRate = 10.00;
            $gross = $booking->total_amount;
            $commission = round($gross * $commissionRate / 100, 2);
            Earning::create([
                'pandit_profile_id' => $this->profile()->id,
                'booking_id' => $booking->id,
                'gross_amount' => $gross,
                'commission_rate' => $commissionRate,
                'commission_amount' => $commission,
                'net_amount' => $gross - $commission,
                'status' => 'paid',
                'paid_at' => now(),
            ]);
        }

        return back()->with('success', 'Booking marked as completed!');
    }

    // ─── Availability Management ───────────────────────
    public function availability()
    {
        $p = $this->profile();
        $slots = $p->availabilities()->orderBy('day_of_week')->orderBy('start_time')->get();
        $blockedDates = $p->blockedDates()->where('blocked_date', '>=', today())->orderBy('blocked_date')->get();
        return view('pandit.availability', compact('slots', 'blockedDates'));
    }

    public function saveAvailability(SaveAvailabilityRequest $request)
    {
        $p = $this->profile();
        $validated = $request->validated();

        // Replace all availability slots
        $p->availabilities()->delete();

        foreach ($validated['slots'] as $slot) {
            if (!empty($slot['active'])) {
                Availability::create([
                    'pandit_profile_id' => $p->id,
                    'day_of_week' => $slot['day'],
                    'start_time' => $slot['start'],
                    'end_time' => $slot['end'],
                    'is_available' => true,
                ]);
            }
        }

        return back()->with('success', 'Availability updated!');
    }

    public function blockDate(BlockDateRequest $request)
    {
        $validated = $request->validated();

        $this->profile()->blockedDates()->create([
            'blocked_date' => $validated['blocked_date'],
            'reason' => $validated['reason'] ?? null,
        ]);

        return back()->with('success', 'Date blocked successfully.');
    }

    public function unblockDate(BlockedDate $blockedDate)
    {
        if ($blockedDate->pandit_profile_id !== $this->profile()->id) abort(403);
        $blockedDate->delete();
        return back()->with('success', 'Date unblocked.');
    }

    // ─── Calendar (JSON for FullCalendar) ──────────────
    public function calendar()
    {
        return view('pandit.calendar');
    }

    public function calendarEvents(Request $request)
    {
        $p = $this->profile();
        $events = [];

        // Bookings as events
        $bookings = $p->bookings()
            ->with('user', 'service')
            ->whereIn('status', ['pending', 'confirmed', 'completed'])
            ->get();

        foreach ($bookings as $b) {
            $start = Carbon::parse($b->booking_date . ' ' . $b->booking_time);
            $end = $b->booking_end_time ? Carbon::parse($b->booking_date . ' ' . $b->booking_end_time) : $start->copy()->addHours($b->duration_hours ?? 2);
            $events[] = [
                'id' => 'booking_' . $b->id,
                'title' => ($b->service->name ?? 'Puja') . ' - ' . $b->user->name,
                'start' => $start->toIso8601String(),
                'end' => $end->toIso8601String(),
                'color' => $b->status === 'confirmed' ? '#16a34a' : ($b->status === 'pending' ? '#eab308' : '#3b82f6'),
                'extendedProps' => ['type' => 'booking', 'status' => $b->status],
            ];
        }

        // Blocked dates as all-day events
        foreach ($p->blockedDates as $bd) {
            $events[] = [
                'id' => 'blocked_' . $bd->id,
                'title' => 'Blocked' . ($bd->reason ? ': ' . $bd->reason : ''),
                'start' => $bd->blocked_date->toDateString(),
                'allDay' => true,
                'color' => '#9ca3af',
                'extendedProps' => ['type' => 'blocked'],
            ];
        }

        return response()->json($events);
    }

    // ─── Earnings ──────────────────────────────────────
    public function earnings()
    {
        $p = $this->profile();
        $totalEarnings = $p->earnings()->sum('net_amount');
        $monthEarnings = $p->earnings()->whereMonth('created_at', now()->month)->sum('net_amount');
        $weekEarnings = $p->earnings()->where('created_at', '>=', now()->startOfWeek())->sum('net_amount');
        $totalCommission = $p->earnings()->sum('commission_amount');

        $recentEarnings = $p->earnings()->with('booking.service', 'booking.user')->latest()->paginate(15);

        // Monthly chart — last 6 months
        $chartLabels = [];
        $chartData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $chartLabels[] = $month->format('M Y');
            $chartData[] = $p->earnings()->whereMonth('created_at', $month->month)->whereYear('created_at', $month->year)->sum('net_amount');
        }

        return view('pandit.earnings', compact('totalEarnings', 'monthEarnings', 'weekEarnings', 'totalCommission', 'recentEarnings', 'chartLabels', 'chartData'));
    }

    // ─── Reviews ───────────────────────────────────────
    public function reviews()
    {
        $p = $this->profile();
        $reviews = $p->reviews()->with('user', 'booking.service')->latest()->paginate(15);
        $avgRating = $p->reviews()->avg('rating') ?? 0;
        $totalReviews = $p->reviews()->count();

        $ratingBreakdown = [];
        for ($i = 5; $i >= 1; $i--) {
            $ratingBreakdown[$i] = $p->reviews()->where('rating', $i)->count();
        }

        return view('pandit.reviews', compact('reviews', 'avgRating', 'totalReviews', 'ratingBreakdown'));
    }

    // ─── Get Available Slots (API for booking form) ────
    public function getSlots(GetSlotsRequest $request)
    {
        $validated = $request->validated();

        $p = $this->profile();
        $date = Carbon::parse($validated['date']);
        $dayOfWeek = $date->dayOfWeek;

        // Check if date is blocked
        if ($p->blockedDates()->where('blocked_date', $date->toDateString())->exists()) {
            return response()->json(['slots' => [], 'message' => 'This date is blocked']);
        }

        // Get availability for this day
        $availability = $p->availabilities()->where('day_of_week', $dayOfWeek)->where('is_available', true)->first();
        if (!$availability) {
            return response()->json(['slots' => [], 'message' => 'Not available on this day']);
        }

        // Generate slots
        $start = Carbon::parse($availability->start_time);
        $end = Carbon::parse($availability->end_time);
        $duration = (int) ($validated['duration'] ?? 2);

        $existingBookings = $p->bookings()
            ->where('booking_date', $date->toDateString())
            ->whereIn('status', ['pending', 'confirmed'])
            ->get();

        $slots = [];
        while ($start->copy()->addHours($duration)->lte($end)) {
            $slotEnd = $start->copy()->addHours($duration);
            $isBooked = $existingBookings->contains(function ($b) use ($start, $slotEnd) {
                $bStart = Carbon::parse($b->booking_time);
                $bEnd = $b->booking_end_time ? Carbon::parse($b->booking_end_time) : $bStart->copy()->addHours($b->duration_hours ?? 2);
                return $start->lt($bEnd) && $slotEnd->gt($bStart);
            });

            $slots[] = [
                'time' => $start->format('H:i'),
                'label' => $start->format('h:i A') . ' – ' . $slotEnd->format('h:i A'),
                'available' => !$isBooked,
            ];

            $start->addHour();
        }

        return response()->json(['slots' => $slots]);
    }
}
