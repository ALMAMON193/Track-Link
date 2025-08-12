<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class VerifyEmailRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email|exists:users,email',
            'otp'   => 'required|digits:4',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Email address is required.',
            'email.email' => 'Email format is invalid.',
            'email.exists' => 'This email is not registered.',
            'otp.required' => 'OTP is required.',
            'otp.digits' => 'OTP must be a 4-digit number.',
        ];
    }
}
