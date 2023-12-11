<?php

namespace App\Http\Resources\ApplicantIndividual;

use App\Models\Soate;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Crypt;

class ItemAIAddressResource extends JsonResource
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
            'place_work_study' => $this->place_work_study,
            'position' => $this->position,
            'registration_address' => $this->registration_address,
            'postal_address' => $this->postal_address,
            'soate_registration_address' => Soate::query()
                ->where('id', $this->soate_registration_address)
                ->first(),
            'soate_postal_address' => Soate::query()
                ->where('id', $this->soate_postal_address)
                ->first(),
        ];
    }

    public function withResponse($request, $response)
    {
        $response->header('Charset', 'utf-8');
        $response->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }
}
