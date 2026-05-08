<?php

namespace App\Http\Controllers;

class PageController extends Controller
{
    public function home()
    {
        return view('welcome');
    }

    public function adminLogin()
    {
        return view('admin.login');
    }

    public function dashboard()
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($user->role === 'pandit') {
            $profile = $user->panditProfile;

            if (!$profile || $profile->approval_status !== 'approved') {
                return redirect()->route('pandit.pending');
            }

            return redirect()->route('pandit.dashboard');
        }

        return redirect()->route('user.dashboard');
    }

    public function userDashboard()
    {
        return view('user.dashboard');
    }

    public function userBookings()
    {
        return view('user.bookings');
    }

    public function pendingPandits()
    {
        return redirect()->route('admin.pandits', ['status' => 'pending']);
    }
}
