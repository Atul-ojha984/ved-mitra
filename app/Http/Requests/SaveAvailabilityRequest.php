<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\SanitizesFormInput;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class SaveAvailabilityRequest extends FormRequest
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
            'slots' => ['required', 'array', 'max:21'],
            'slots.*.day' => ['required', 'integer', 'between:0,6'],
            'slots.*.start' => ['required', 'date_format:H:i'],
            'slots.*.end' => ['required', 'date_format:H:i'],
            'slots.*.active' => ['sometimes', 'boolean'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator): void {
                foreach ($this->input('slots', []) as $index => $slot) {
                    if (($slot['end'] ?? null) <= ($slot['start'] ?? null)) {
                        $validator->errors()->add("slots.$index.end", 'Slot end time must be after the start time.');
                    }
                }
            },
        ];
    }
}
