<?php

namespace App\Http\Resources\ApplicantLegalEntity;

use Illuminate\Http\Resources\Json\JsonResource;

class ItemALEResource extends JsonResource
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
            'olf_id' => $this->olf_id,
            'name' => $this->name,
            'name_kg' => $this->name_kg,
            'last_name_manager' => $this->last_name_manager,
            'name_manager' => $this->name_manager,
            'patronymic_manager' => $this->patronymic_manager,
            'position_manager' => $this->position_manager,
            'inn' => $this->inn,
            'okpo' => $this->okpo,
            'date_registration' => $this->date_registration,
            'soate_registration_address' => $this->legal_address,
            'soate_postal_address' => $this->postal_address,
            'registration_address' => $this->legal_address,
            'postal_address' => $this->postal_address,
            'note' => $this->note,
            'status_id' => $this->status_id,
        ];
    }

    public function withResponse($request, $response)
    {
        $response->header('Charset', 'utf-8');
        $response->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }
}
