<?php

namespace App\Http\Requests\PersonsInterest;

use Illuminate\Foundation\Http\FormRequest;

class PersonInterestReqauest extends FormRequest
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
            'patronymic' => 'string|max:255',
            'inn' => 'string|max:255',
            'a_individual_id' => 'exists:applicant_individuals,id',
            'a_legal_ent_id' => 'exists:applicant_legal_entities,id',
            'dok_id' => 'exists:degree_of_kinships,id',
            'date_birth' => 'date',
            'gender_id' => 'exists:genders,id',
            'place_work_study' => 'string|max:255',
            'position' => 'string|max:255',
            'registration_address' => 'string|max:255',
            'postal_address' => 'string|max:255',
            'note' => 'string|max:255',
        ];

    }
}
