<?php

namespace App\Http\Resources\References;

use Illuminate\Http\Resources\Json\JsonResource;

class DefendantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'last_name' => $this->last_name,
            'patronymic' => $this->patronymic,
            'born_date' => $this->born_date,
            'inn' => $this->inn,
            'gender' => new GenderResource($this->gender),
            'government_agency_id' => new GovernmentAgencyResource($this->organizationData),
            'position_governmental_id' => new PositionGovernmentalResource($this->positionGovernmental)
        ];
    }
}
