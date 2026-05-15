<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Show the authenticated user's profile.
     */
    public function show()
    {
        $user = Auth::user();
        $upcomingBookings = $user->bookings()
            ->with(['pandit.user', 'service'])
            ->where('booking_date', '>=', now()->toDateString())
            ->whereIn('status', ['pending', 'confirmed'])
            ->orderBy('booking_date')
            ->get();

        $pastBookings = $user->bookings()
            ->with(['pandit.user', 'service'])
            ->where(function ($q) {
                $q->where('booking_date', '<', now()->toDateString())
                  ->orWhereIn('status', ['completed', 'cancelled']);
            })
            ->orderByDesc('booking_date')
            ->limit(10)
            ->get();

        return view('profile.show', compact('user', 'upcomingBookings', 'pastBookings'));
    }

    /**
     * Update the authenticated user's profile.
     */
    public function update(ProfileUpdateRequest $request)
    {
        $user = Auth::user();

        $validated = $request->validated();

        $user->name = $validated['name'];
        $user->phone = $validated['phone'] ?? $user->phone;

        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $user->avatar = $request->file('avatar')->store('avatars', 'public');
        }

        $user->save();

        return back()->with('success', 'Profile updated successfully!');
    }
}
