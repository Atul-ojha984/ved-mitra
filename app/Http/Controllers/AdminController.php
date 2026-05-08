<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PanditProfile;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Support\Facades\Mail;
use App\Mail\PanditApproved;
use App\Mail\PanditRejected;
use Carbon\Carbon;

class AdminController extends Controller
{
    // ─── Dashboard ─────────────────────────────────────
    public function dashboard()
    {
        $totalUsers = User::where('role', 'user')->count();
        $totalPandits = PanditProfile::where('approval_status', 'approved')->count();
        $pendingApprovals = PanditProfile::where('approval_status', 'pending')->count();
        $totalBookings = Booking::count();
        $totalRevenue = Payment::where('status', 'successful')->sum('amount');
        $todayBookings = Booking::whereDate('created_at', today())->count();
        $monthRevenue = Payment::where('status', 'successful')->whereMonth('created_at', now()->month)->sum('amount');

        // Charts data — last 7 days
        $chartLabels = [];
        $chartBookings = [];
        $chartRevenue = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $chartLabels[] = $date->format('d M');
            $chartBookings[] = Booking::whereDate('created_at', $date)->count();
            $chartRevenue[] = Payment::where('status', 'successful')->whereDate('created_at', $date)->sum('amount');
        }

        $recentBookings = Booking::with(['user', 'service', 'pandit.user'])->latest()->take(10)->get();

        return view('admin.dashboard', compact(
            'totalUsers', 'totalPandits', 'pendingApprovals', 'totalBookings',
            'totalRevenue', 'todayBookings', 'monthRevenue', 'recentBookings',
            'chartLabels', 'chartBookings', 'chartRevenue'
        ));
    }

    // ─── User Management ───────────────────────────────
    public function users(Request $request)
    {
        $query = User::where('role', '!=', 'admin');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%$s%")->orWhere('email', 'like', "%$s%")->orWhere('phone', 'like', "%$s%");
            });
        }
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        if ($request->filled('status')) {
            $query->where('account_status', $request->status);
        }

        $users = $query->latest()->paginate(20)->appends($request->query());
        return view('admin.users', compact('users'));
    }

    public function toggleUserStatus(User $user, Request $request)
    {
        $request->validate(['status' => 'required|in:active,suspended,banned']);
        $user->update(['account_status' => $request->status]);
        return back()->with('success', $user->name . ' status changed to ' . $request->status);
    }

    public function deleteUser(User $user)
    {
        if ($user->role === 'admin') abort(403);
        $user->delete();
        return back()->with('success', 'User deleted successfully.');
    }

    // ─── Pandit Management ─────────────────────────────
    public function pandits(Request $request)
    {
        $query = PanditProfile::with('user', 'services');

        if ($request->filled('status')) {
            $query->where('approval_status', $request->status);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->whereHas('user', fn($q) => $q->where('name', 'like', "%$s%")->orWhere('email', 'like', "%$s%"));
        }

        $pandits = $query->latest()->paginate(20)->appends($request->query());
        $counts = [
            'all' => PanditProfile::count(),
            'pending' => PanditProfile::where('approval_status', 'pending')->count(),
            'approved' => PanditProfile::where('approval_status', 'approved')->count(),
            'rejected' => PanditProfile::where('approval_status', 'rejected')->count(),
        ];
        return view('admin.pandits', compact('pandits', 'counts'));
    }

    public function reviewPandit(PanditProfile $pandit)
    {
        $pandit->load('user', 'services');
        return view('admin.review-pandit', compact('pandit'));
    }

    public function approvePandit(PanditProfile $pandit)
    {
        $pandit->update([
            'approval_status' => 'approved',
            'verified' => true,
            'approved_at' => now(),
            'approved_by' => auth()->id(),
            'rejection_reason' => null,
        ]);
        try { Mail::to($pandit->user->email)->send(new PanditApproved($pandit)); } catch (\Exception $e) { \Log::warning('Approval email failed: ' . $e->getMessage()); }
        return back()->with('success', $pandit->user->name . ' has been approved!');
    }

    public function rejectPandit(Request $request, PanditProfile $pandit)
    {
        $pandit->update([
            'approval_status' => 'rejected',
            'verified' => false,
            'rejection_reason' => $request->rejection_reason,
        ]);
        try { Mail::to($pandit->user->email)->send(new PanditRejected($pandit)); } catch (\Exception $e) { \Log::warning('Rejection email failed: ' . $e->getMessage()); }
        return back()->with('success', $pandit->user->name . ' has been rejected.');
    }

    public function suspendPandit(PanditProfile $pandit)
    {
        $pandit->update(['approval_status' => 'rejected', 'verified' => false]);
        $pandit->user->update(['account_status' => 'suspended']);
        return back()->with('success', $pandit->user->name . ' has been suspended.');
    }

    // ─── Booking Management ────────────────────────────
    public function bookings(Request $request)
    {
        $query = Booking::with(['user', 'pandit.user', 'service']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->whereHas('user', fn($q) => $q->where('name', 'like', "%$s%"));
        }

        $bookings = $query->latest()->paginate(20)->appends($request->query());
        $counts = [
            'all' => Booking::count(),
            'pending' => Booking::where('status', 'pending')->count(),
            'confirmed' => Booking::where('status', 'confirmed')->count(),
            'completed' => Booking::where('status', 'completed')->count(),
            'cancelled' => Booking::where('status', 'cancelled')->count(),
        ];
        return view('admin.bookings', compact('bookings', 'counts'));
    }

    public function cancelBooking(Booking $booking)
    {
        $booking->update(['status' => 'cancelled']);
        return back()->with('success', 'Booking #BK-' . str_pad($booking->id, 4, '0', STR_PAD_LEFT) . ' cancelled.');
    }

    // ─── Payment Management ────────────────────────────
    public function payments(Request $request)
    {
        $query = Payment::with(['booking.user', 'booking.service']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $payments = $query->latest()->paginate(20)->appends($request->query());
        $stats = [
            'total' => Payment::where('status', 'successful')->sum('amount'),
            'pending' => Payment::where('status', 'pending')->sum('amount'),
            'count' => Payment::where('status', 'successful')->count(),
        ];
        return view('admin.payments', compact('payments', 'stats'));
    }
}
