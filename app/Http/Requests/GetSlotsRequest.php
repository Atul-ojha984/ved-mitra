<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\SanitizesFormInput;
use Illuminate\Foundation\Http\FormRequest;

class GetSlotsRequest extends FormRequest
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
            'date' => ['required', 'date'],
            'duration' => ['nullable', 'integer', 'min:1', 'max:8'],
        ];
    }
}
