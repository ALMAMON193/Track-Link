<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'name'                 => 'required|string|max:255',
            'email'                => 'required|email|unique:users,email',
            'password'             => 'required|string|min:8|confirmed',
            'terms_and_conditions' => 'required|accepted',
            'user_type'            => 'required|in:trucker,shipper',
            'company_name'         => 'nullable|string|max:255',

            // Truckers only fields
            'driver_details.driver_license' => 'required_if:user_type,trucker|file|mimes:pdf,doc,docx,jpeg,png,jpg,gif,svg|max:5120',
            'driver_details.license_number' => 'required_if:user_type,trucker|string|max:255',
            'driver_details.state_of_issue' => 'required_if:user_type,trucker|string|max:255',
            'driver_details.expiration_date' => 'required_if:user_type,trucker',

            'experience_preferences.experience' => 'required_if:user_type,trucker|string|max:255',
            'experience_preferences.vehicle_type' => 'required_if:user_type,trucker|in:semi_truck,box_truck,flatbed,refrigerated',
            'experience_preferences.service_area' => 'required_if:user_type,trucker|in:regional,international,national',
            'experience_preferences.additional_information' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Name is required.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Email format is invalid.',
            'email.unique' => 'This email is already registered.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
            'terms_and_conditions.required' => 'You must accept the terms and conditions.',
            'terms_and_conditions.accepted' => 'You must accept the terms and conditions.',
            'user_type.required' => 'User type is required.',
            'user_type.in' => 'Invalid user type selected.',

            // Custom messages for trucker fields
            'driver_details.driver_license.required_if' => 'Driver license is required for truckers.',
            'driver_details.license_number.required_if' => 'License number is required for truckers.',
            'driver_details.state_of_issue.required_if' => 'State of issue is required for truckers.',
            'driver_details.expiration_date.required_if' => 'Expiration date is required for truckers.',
            'driver_details.expiration_date.date' => 'Expiration date must be a valid date.',

            'experience_preferences.experience.required_if' => 'Experience is required for truckers.',
            'experience_preferences.vehicle_type.required_if' => 'Vehicle type is required for truckers.',
            'experience_preferences.vehicle_type.in' => 'Invalid vehicle type selected.',
            'experience_preferences.service_area.required_if' => 'Service area is required for truckers.',
            'experience_preferences.service_area.in' => 'Invalid service area selected.',
        ];
    }
}
