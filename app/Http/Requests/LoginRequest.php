<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'nik' => ['required', 'exists:user_credentials,nik'],
            'password' => 'required'
        ];
    }

    public function messages()
    {
        return [
          'nik.required' => 'NIK is required',
          'nik.exists' => 'NIK is not exists',
          'password' => 'Password is required'
        ];
    }

}
