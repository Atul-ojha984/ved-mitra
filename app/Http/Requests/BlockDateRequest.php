<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\SanitizesFormInput;
use Illuminate\Foundation\Http\FormRequest;

class BlockDateRequest extends FormRequest
{
    use SanitizesFormInput;

    public function authorize(): bool
    {
        return $this->user()?->role === 'pandit';
    }

    protected function prepareForValidation(): void
    {
        $this->sanitizeInput();
    }

    public function rules(): array
    {
        return [
            'blocked_date' => ['required', 'date', 'after_or_equal:today'],
            'reason' => ['nullable', 'string', 'max:255'],
        ];
    }
}
