<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\SanitizesFormInput;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    use SanitizesFormInput;

    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    protected function prepareForValidation(): void
    {
        $this->sanitizeInput();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:100'],
            'phone' => [
                'nullable',
                'string',
                'regex:/^[6-9]\d{9}$/',
                Rule::unique('users', 'phone')->ignore($this->user()?->id),
            ],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'phone.regex' => 'Please enter a valid Indian 10-digit mobile number starting with 6, 7, 8, or 9.',
            'phone.unique' => 'This phone number is already registered.',
            'avatar.max' => 'Profile photo must not be larger than 2 MB.',
        ];
    }
}
