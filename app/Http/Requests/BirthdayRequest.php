<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BirthdayRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */

    public function rules(): array
    {
        return [
            'birthdays' => 'required|array',
            'birthdays.*' => [
                'required',
                'date_format:Y-m-d',
                'before_or_equal:today'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'birthdays.*.date_format' => 'Неправильный формат даты',
            'birthdays.*.before_or_equal' => 'Дата не может быть указана в будущем',
        ];
    }
}
