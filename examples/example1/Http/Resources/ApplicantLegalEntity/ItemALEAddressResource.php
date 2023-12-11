<?php

namespace App\Http\Resources\ApplicantLegalEntity;

use App\Models\Soate;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemALEAddressResource extends JsonResource
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
            'registration_address' => $this->registration_address,
            'postal_address' => $this->postal_address,
            'soate_registration_address' => Soate::query()
                ->where('id', $this->soate_registration_address)
                ->first(),
            'soate_postal_address' => Soate::query()
                ->where('id', $this->soate_postal_address)
                ->first(),
            'last_name_manager' => $this->last_name_manager,
            'name_manager' => $this->name_manager,
            'patronymic_manager' => $this->patronymic_manager,
            'position_manager' => $this->position_manager,
        ];
    }

    public function withResponse($request, $response)
    {
        $response->header('Charset', 'utf-8');
        $response->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }
}
