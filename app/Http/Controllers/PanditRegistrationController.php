<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\PanditRegisterRequest;
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
    public function register(PanditRegisterRequest $request)
    {
        $validated = $request->validated();

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
