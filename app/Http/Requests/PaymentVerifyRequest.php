<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\SanitizesFormInput;
use Illuminate\Foundation\Http\FormRequest;

class PaymentVerifyRequest extends FormRequest
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
            'razorpay_payment_id' => ['required', 'string', 'max:191'],
            'razorpay_order_id' => ['required', 'string', 'max:191'],
            'razorpay_signature' => ['required', 'string', 'max:255'],
        ];
    }
}
