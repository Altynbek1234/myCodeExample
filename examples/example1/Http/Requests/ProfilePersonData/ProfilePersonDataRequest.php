<?php

namespace App\Http\Requests\ProfilePersonData;

use Illuminate\Foundation\Http\FormRequest;

class ProfilePersonDataRequest extends FormRequest
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
        //'appeal_id' => 'required|exists:appeals,id',
        return [
            'a_individual_id' => 'exists:applicant_individuals,id|nullable',
            'appeal_id' => 'exists:appeals,id|nullable',
            'unknown_age' => 'boolean',
            'not_wont_age' => 'boolean',
            'age' => 'integer|nullable|not_in:80000,80001',
            'nationality' => 'string|max:255',
            'other_nationality' => 'string|max:255',
            'social_status' => 'string|max:255',
            'other_social_status' => 'string|max:255',
            'family_status' => 'string|max:255',
            'other_family_status' => 'string|max:255',
            'level_of_education' => 'string|max:255',
            'other_level_of_education' => 'string|max:255',
            'income_levels' => 'string|max:255',
            'other_income_level' => 'string|max:255',
            'migration_status' => 'string|max:255',
            'other_migration_status' => 'string|max:255',
            'purpose_of_migrant' => 'string|max:255',
            'other_purpose_of_migrant' => 'string|max:255',
            'getting_disabilities' => 'string|max:255',
            'other_getting_disabilities' => 'string|max:255',
            'limited_health' => 'string|max:255',
            'other_limited_healths' => 'string|max:255',
            'sickness' => 'string|max:255',
            'other_sickness' => 'string|max:255',
            'registered_psychiatric' => 'string|max:255',
            'date_registered_psychiatric' => 'date',
            'other_registered_psychiatric' => 'string|max:255',
            'criminal_record' => 'string|max:255',
            'other_criminal_record' => 'string|max:255',
            'vulnerable_group' => 'string|max:255',
            'other_vulnerable_group' => 'string|max:255',
            'group_membership' => 'string|max:255',
            'not_group_membership' => 'boolean',
            'other_group_memberships' => 'string|max:255',
            'note' => 'string|max:255',
            'soate_registration_address' => 'string|max:255',
            'soate_postal_address' => 'string|max:255',
            'registration_address' => 'string|max:255',
            'postal_address' => 'string|max:255',
            'position' => 'string|max:255',
            'place_work_study' => 'string|max:255',
        ];

    }
}
