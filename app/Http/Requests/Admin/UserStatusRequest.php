<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Concerns\SanitizesFormInput;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserStatusRequest extends FormRequest
{
    use SanitizesFormInput;

    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    protected function prepareForValidation(): void
    {
        $this->sanitizeInput();
    }

    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in(['active', 'suspended', 'banned'])],
        ];
    }
}
