<?php

namespace App\Http\Requests\Trucker;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePersonalInformationRequest extends FormRequest
{
    public function authorize(): true
    {
        return true; // Adjust based on your authentication logic
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:50',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore(auth()->id())],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:50',
            'about' => 'nullable|string|max:300',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:20048',
        ];
    }
}
