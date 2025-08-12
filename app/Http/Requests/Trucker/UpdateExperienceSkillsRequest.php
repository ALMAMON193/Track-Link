<?php

namespace App\Http\Requests\Trucker;

use Illuminate\Foundation\Http\FormRequest;

class UpdateExperienceSkillsRequest extends FormRequest
{
    public function authorize(): true
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'experience' => 'required|string',
        ];
    }
}
