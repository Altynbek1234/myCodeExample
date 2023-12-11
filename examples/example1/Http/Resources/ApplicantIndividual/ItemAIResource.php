<?php

namespace App\Http\Resources\ApplicantIndividual;

use App\Http\Resources\ProfilePersonData\ProfilePersonDataResource;
use App\Models\ProfilePersonData;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Crypt;

class ItemAIResource extends JsonResource
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
            'profile' => $this->getProfile(),
            'citizenship_id' => $this->citizenship_id,
            'date_birth' => $this->date_birth,
            'gender_id' => $this->gender_id,
            'place_work_study' => $this->place_work_study,
            'position' => $this->position,
            'registration_address' => $this->registration_address,
            'postal_address' => $this->postal_address,
            'note' => $this->note,
            'status_id' => $this->status_id,
            'type' => $this->type,
            'contact' => $this->getNumber(),
        ];
    }

    public function getProfile()
    {
        $profileId = $this->pivot ? $this->pivot->profile_id : null;
        if ($profileId) {
            return ProfilePersonDataResource::make(ProfilePersonData::find($profileId));
        }
        return null;
    }

    public function withResponse($request, $response)
    {
        $response->header('Charset', 'utf-8');
        $response->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }
}
