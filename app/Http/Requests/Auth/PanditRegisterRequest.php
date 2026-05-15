<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\Concerns\SanitizesFormInput;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class PanditRegisterRequest extends FormRequest
{
    use SanitizesFormInput;

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->sanitizeInput();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:100'],
            'email' => ['required', 'email:rfc', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'regex:/^[6-9]\d{9}$/', 'unique:users,phone'],
            'alternate_phone' => ['nullable', 'string', 'regex:/^[6-9]\d{9}$/'],
            'password' => ['required', 'string', Password::min(8), 'confirmed'],
            'gender' => ['required', Rule::in(['male', 'female', 'other'])],
            'date_of_birth' => ['required', 'date', 'before:today'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],

            'address' => ['required', 'string', 'max:500'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:100'],
            'pincode' => ['required', 'string', 'regex:/^[1-9][0-9]{5}$/'],
            'location_lat' => ['nullable', 'numeric', 'between:-90,90'],
            'location_lng' => ['nullable', 'numeric', 'between:-180,180'],

            'experience_years' => ['required', 'integer', 'min:0', 'max:60'],
            'qualification' => ['required', 'string', 'max:255'],
            'specialization' => ['required', 'string', 'max:255'],
            'languages' => ['required', 'string', 'max:255'],
            'bio' => ['required', 'string', 'max:2000'],
            'consultation_fee' => ['required', 'numeric', 'min:0', 'max:100000'],
            'available_timings' => ['nullable', 'string', 'max:255'],
            'services' => ['required', 'array', 'min:1'],
            'services.*' => ['integer', 'distinct', Rule::exists('services', 'id')],

            'aadhaar_document' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'pan_document' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'certificate_document' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],

            'website_url' => ['nullable', 'url', 'max:255'],
            'youtube_url' => ['nullable', 'url', 'max:255'],
            'instagram_url' => ['nullable', 'url', 'max:255'],
            'facebook_url' => ['nullable', 'url', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'phone.regex' => 'Please enter a valid Indian 10-digit mobile number starting with 6, 7, 8, or 9.',
            'phone.unique' => 'This phone number is already registered.',
            'alternate_phone.regex' => 'Alternate phone must be a valid Indian 10-digit mobile number.',
            'pincode.regex' => 'Please enter a valid 6-digit Indian pincode.',
            'aadhaar_document.max' => 'Aadhaar document must not be larger than 5 MB.',
            'profile_photo.max' => 'Profile photo must not be larger than 2 MB.',
        ];
    }
}
