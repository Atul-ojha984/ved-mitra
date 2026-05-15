<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\SanitizesFormInput;
use Illuminate\Foundation\Http\FormRequest;

class KundliGenerateRequest extends FormRequest
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
            'full_name' => ['required', 'string', 'min:2', 'max:100'],
            'dob' => ['required', 'date', 'before:today'],
            'birth_time' => ['required', 'date_format:H:i'],
            'birth_place' => ['required', 'string', 'min:2', 'max:150'],
        ];
    }
}
