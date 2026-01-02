<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'old_password' => ['required', 'string',
                    function ($attribute, $value, $fail) {
                        if (!\Hash::check($value, $this->user()?->pass)) {
                             $fail('Old password is incorrect');
                        }
                    }
                ],
            'new_password' => 'required|string|min:8|confirmed',
            'new_password_confirmation' => 'required|string|min:8',
        ];
    }

    public function messages()
    {
        return [
            'old_password.required' => 'Old password is required',
            'new_password.required' => 'New password is required',
            'new_password.min' => 'New password must be at least 8 characters',
            'new_password.confirmed' => 'New password confirmation does not match',
            'new_password_confirmation.required' => 'New password confirmation is required',
            'new_password_confirmation.min' => 'New password confirmation must be at least 8 characters',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
