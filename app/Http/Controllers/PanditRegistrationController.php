<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\PanditProfile;
use App\Models\Service;

class PanditRegistrationController extends Controller
{
    /**
     * Show the pandit registration form.
     */
    public function showForm()
    {
        $services = Service::all();
        return view('auth.pandit-register', compact('services'));
    }

    /**
     * Handle pandit registration.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            // Personal
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'phone' => 'required|string|max:15',
            'alternate_phone' => 'nullable|string|max:15',
            'password' => 'required|string|min:8|confirmed',
            'gender' => 'required|in:male,female,other',
            'date_of_birth' => 'required|date|before:today',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            // Location
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'pincode' => 'required|string|max:10',
            'location_lat' => 'nullable|numeric',
            'location_lng' => 'nullable|numeric',

            // Professional
            'experience_years' => 'required|integer|min:0|max:60',
            'qualification' => 'required|string|max:255',
            'specialization' => 'required|string|max:255',
            'languages' => 'required|string|max:255',
            'bio' => 'required|string|max:2000',
            'consultation_fee' => 'required|numeric|min:0',
            'available_timings' => 'nullable|string|max:255',
            'services' => 'required|array|min:1',
            'services.*' => 'exists:services,id',

            // Documents
            'aadhaar_document' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'pan_document' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'certificate_document' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',

            // Social
            'website_url' => 'nullable|url|max:255',
            'youtube_url' => 'nullable|url|max:255',
            'instagram_url' => 'nullable|url|max:255',
            'facebook_url' => 'nullable|url|max:255',
        ]);

        // Create User
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'role' => 'pandit',
        ]);

        // Handle file uploads
        $profilePhoto = $request->hasFile('profile_photo')
            ? $request->file('profile_photo')->store('pandits/photos', 'public') : null;
        $aadhaar = $request->file('aadhaar_document')->store('pandits/documents', 'public');
        $pan = $request->hasFile('pan_document')
            ? $request->file('pan_document')->store('pandits/documents', 'public') : null;
        $certificate = $request->hasFile('certificate_document')
            ? $request->file('certificate_document')->store('pandits/documents', 'public') : null;

        // Create Profile
        $profile = PanditProfile::create([
            'user_id' => $user->id,
            'bio' => $validated['bio'],
            'experience_years' => $validated['experience_years'],
            'qualification' => $validated['qualification'],
            'specialization' => $validated['specialization'],
            'languages' => $validated['languages'],
            'consultation_fee' => $validated['consultation_fee'],
            'available_timings' => $validated['available_timings'] ?? null,
            'gender' => $validated['gender'],
            'date_of_birth' => $validated['date_of_birth'],
            'alternate_phone' => $validated['alternate_phone'] ?? null,
            'address' => $validated['address'],
            'city' => $validated['city'],
            'state' => $validated['state'],
            'pincode' => $validated['pincode'],
            'location_lat' => $validated['location_lat'] ?? null,
            'location_lng' => $validated['location_lng'] ?? null,
            'profile_photo' => $profilePhoto,
            'aadhaar_document' => $aadhaar,
            'pan_document' => $pan,
            'certificate_document' => $certificate,
            'website_url' => $validated['website_url'] ?? null,
            'youtube_url' => $validated['youtube_url'] ?? null,
            'instagram_url' => $validated['instagram_url'] ?? null,
            'facebook_url' => $validated['facebook_url'] ?? null,
            'approval_status' => 'pending',
        ]);

        // Attach selected services
        $profile->services()->attach($validated['services']);

        // Log in the pandit
        Auth::login($user);

        return redirect()->route('pandit.pending');
    }

    /**
     * Show the "pending approval" status page.
     */
    public function pendingPage()
    {
        $user = auth()->user();
        $profile = $user->panditProfile;
        return view('pandit.pending', compact('user', 'profile'));
    }
}
