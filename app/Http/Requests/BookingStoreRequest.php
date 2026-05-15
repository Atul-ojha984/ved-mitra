<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\SanitizesFormInput;
use App\Models\PanditProfile;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BookingStoreRequest extends FormRequest
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
        /** @var PanditProfile|null $pandit */
        $pandit = $this->route('pandit');

        return [
            'service_id' => [
                'required',
                'integer',
                Rule::exists('pandit_services', 'service_id')->where(
                    fn ($query) => $query->where('pandit_profile_id', $pandit?->id)
                ),
            ],
            'booking_date' => ['required', 'date', 'after_or_equal:today'],
            'booking_time' => ['required', 'regex:/^(?:[01]\d|2[0-3]):[0-5]\d(?::[0-5]\d)?$/'],
            'address' => ['required', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'service_id.exists' => 'Please select a service offered by this Pandit.',
            'booking_time.regex' => 'Please select a valid booking time.',
        ];
    }
}
