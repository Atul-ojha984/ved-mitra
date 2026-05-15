<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\SanitizesFormInput;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DemoPaymentRequest extends FormRequest
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
            'payment_method' => ['required', Rule::in(['upi', 'card', 'netbanking', 'cod'])],
        ];
    }
}
