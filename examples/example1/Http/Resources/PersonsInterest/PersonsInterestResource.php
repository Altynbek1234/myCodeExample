<?php

namespace App\Http\Resources\PersonsInterest;

use App\Http\Resources\ApplicantIndividual\PersonInfoResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Crypt;

class PersonsInterestResource extends JsonResource
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
            'person_info' => new PersonInfoResource($this->personInfo),
            'a_individual_id' => $this->a_individual_id,
            'a_legal_ent_id' => $this->a_legal_ent_id,
            'dok_id' => $this->dok_id,
            'date_birth' => $this->date_birth,
            'gender_id' => $this->gender_id,
            'place_work_study' => $this->place_work_study,
            'position' => $this->position,
            'registration_address' => $this->registration_address,
            'postal_address' => $this->postal_address,
            'note' => $this->note,
        ];
    }

    public function withResponse($request, $response)
    {
        $response->header('Charset', 'utf-8');
        $response->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }
}
