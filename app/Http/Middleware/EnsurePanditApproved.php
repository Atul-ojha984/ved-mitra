<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePanditApproved
{
    /**
     * Handle an incoming request.
     * Only allow access if the user is a pandit AND their profile is approved.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || $user->role !== 'pandit') {
            abort(403, 'Access denied. Pandit account required.');
        }

        $profile = $user->panditProfile;

        if (!$profile || $profile->approval_status !== 'approved') {
            return redirect()->route('pandit.pending')->with('warning', 'Your account is pending admin approval.');
        }

        return $next($request);
    }
}
