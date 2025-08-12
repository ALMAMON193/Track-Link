<?php

namespace App\Http\Requests\Trucker;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDrivingCredentialsRequest extends FormRequest
{
    public function authorize(): true
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'driver_license'  => 'required|file|mimes:pdf,doc,docx,jpeg,png,jpg,gif,svg|max:5120',
            'license_number'  => 'required|string|max:50|unique:driver_details,license_number,' . auth()->id() . ',user_id',
            'state_of_issue'  => 'required|string|max:100',
            'expiration_date' => 'required|date|after_or_equal:today',
        ];
    }
}
