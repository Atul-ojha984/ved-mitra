<?php

namespace App\Http\Requests\Concerns;

trait SanitizesFormInput
{
    /**
     * Trim strings and strip HTML tags from request input before validation.
     */
    protected function sanitizeInput(array $except = []): void
    {
        $except = array_merge([
            '_method',
            '_token',
            'password',
            'password_confirmation',
            'current_password',
            'token',
            'razorpay_signature',
        ], $except);

        $this->merge($this->sanitizeArray($this->all(), $except));
    }

    private function sanitizeArray(array $input, array $except): array
    {
        foreach ($input as $key => $value) {
            if (in_array((string) $key, $except, true)) {
                continue;
            }

            if (is_array($value)) {
                $input[$key] = $this->sanitizeArray($value, $except);
                continue;
            }

            if (is_string($value)) {
                $value = trim($value);
                $value = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $value) ?? $value;
                $input[$key] = strip_tags($value);
            }
        }

        return $input;
    }
}
