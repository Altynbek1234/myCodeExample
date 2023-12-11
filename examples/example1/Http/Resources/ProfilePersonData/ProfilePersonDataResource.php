<?php

namespace App\Http\Resources\ProfilePersonData;

use App\Http\Resources\ApplicantIndividual\PersonInfoResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Crypt;

class ProfilePersonDataResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'a_individual_id'=> $this->a_individual_id,
            'appeal_id'=> $this->appeal_id,
            'age' => $this->age,
            'nationality' => $this->nationality_id,
            'other_nationality' => $this->other_nationality,
            'social_status' => $this->social_status_id,
            'other_social_status' => $this->other_social_status,
            'family_status' => $this->family_status_id,
            'other_family_status' => $this->other_family_status,
            'level_of_education' => $this->level_of_education_id,
            'other_level_of_education' => $this->other_level_of_education,
            'income_levels' => $this->income_levels_id,
            'other_income_level' => $this->other_income_levels,
            'migration_status' => $this->migration_status_id,
            'other_migration_status' => $this->other_migration_status,
            'purpose_of_migrant' => $this->purpose_of_migrant_id,
            'other_purpose_of_migrant' => $this->other_purpose_of_migrant,
            'getting_disabilities' => $this->getting_disabilities_id,
            'other_getting_disabilities' => $this->other_getting_disabilities,
            'limited_health_id' => $this->limited_health_id,
            'period_of_disability' => $this->period_of_disability,
            'other_limited_healths' => $this->other_limited_healths,
            'sickness' => $this->sickness_id,
            'other_sickness' => $this->other_sickness,
            'registered_psychiatric' => $this->registered_psychiatric,
            'date_registered_psychiatric' => $this->date_registered_psychiatric,
            'other_registered_psychiatric' => $this->other_registered_psychiatric,
            'criminal_record' => $this->criminal_record,
            'other_criminal_record' => $this->other_criminal_record,
            'vulnerable_group' => $this->vulnerable_group_id,
            'other_vulnerable_group' => $this->other_vulnerable_group,
            'group_membership' => $this->group_membership_id,
            'other_group_memberships' => $this->other_group_memberships,
            'note' => $this->note,
            'soate_registration_address' => $this->soate_registration_address,
            'soate_postal_address' => $this->soate_postal_address,
            'registration_address' => $this->registration_address,
            'postal_address' => $this->postal_address,
            'position' => $this->position,
            'place_work_study' => $this->place_work_study,
        ];
    }

    public function withResponse($request, $response)
    {
        $response->header('Charset', 'utf-8');
        $response->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }
}
