<?php

namespace App\Http\Requests\ApplicantIndividual;

use Illuminate\Foundation\Http\FormRequest;

class ApplicantIndivRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'patronymic' => 'nullable|string|max:255',
            'inn' => 'nullable|string|max:255',
            'citizenship_id' => 'nullable|exists:citizenships,id',
            'date_birth' => 'nullable|date',
            'gender_id' => 'nullable|exists:genders,id',
            'place_work_study' => 'nullable|string|max:255',
            'note' => 'nullable|string|max:255',
            'status_id' => 'nullable|required|exists:applicant_statuses,id',
            'position' => 'nullable|string|max:255',
            'registration_address' => 'nullable|string|max:255',
            'postal_address' => 'nullable|string|max:255',
        ];

    }
}
