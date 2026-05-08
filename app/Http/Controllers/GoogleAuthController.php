<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')
            ->scopes(['openid', 'profile', 'email'])
            ->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
        } catch (\Exception $e) {
            Log::error('Google Auth Error: ' . $e->getMessage());
            return redirect()->route('login')->withErrors([
                'email' => 'Google authentication failed: ' . $e->getMessage(),
            ]);
        }

        // Find existing user by google_id or email
        $user = User::where('google_id', $googleUser->getId())
                     ->orWhere('email', $googleUser->getEmail())
                     ->first();

        if ($user) {
            if (!$user->google_id) {
                $user->update(['google_id' => $googleUser->getId()]);
            }
            if ($googleUser->getAvatar()) {
                $user->update(['avatar' => $googleUser->getAvatar()]);
            }
        } else {
            $user = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
                'email_verified_at' => now(),
                'password' => Hash::make(Str::random(24)),
                'role' => 'user',
                'account_status' => 'active',
            ]);
        }

        if ($user->account_status !== 'active') {
            return redirect()->route('login')->withErrors(['email' => 'Your account has been suspended.']);
        }

        Auth::login($user, true);
        return redirect()->intended('/dashboard');
    }
}
