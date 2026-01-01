<?php

namespace App\Http\Requests;

use App\Models\StatusDevice;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Request;

class UserDeviceRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'user_credential_nik' => 'required|exists:user_credentials,nik',
            'device_id' => 'required|string|max:255',
            'device_name' => 'nullable|string|max:255',
            'device_type' => 'nullable|string|max:255',
            'ip_address' => 'nullable|string|max:50',
            'coordinate' => 'nullable|array',
            'fcm_token' => 'nullable|string|max:255',
        ];
    }

    protected function prepareForValidation()
    {
        $coordinate = json_decode( request()->get('coordinate') );
        $this->merge([
            'user_credential_nik' => $this->user()->nik,
            'ip_address' => clientIP(),
            'coordinate' => $coordinate ?? null,
            'fcm_token' => request()->header('fcm_token') ?? null,
            'status' => \App\Models\Enum\StatusDevice::INACTIVE->value,

        ]);
    }

    public function authorize(): bool
    {
        return true;
    }
}
