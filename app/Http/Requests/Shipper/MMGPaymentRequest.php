<?php

namespace App\Http\Requests\Shipper;

use Illuminate\Foundation\Http\FormRequest;

class MMGPaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Add auth check if needed
    }

    public function rules(): array
    {
        return [
            'amount' => 'required|numeric|min:1',
            'currency' => 'required|string|in:GYD',
            'customer_msisdn' => 'required|string|min:7|max:15',
            'description' => 'nullable|string|max:255',
        ];
    }
}
