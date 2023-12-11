<?php

namespace App\Http\Resources;

use App\Http\Resources\ApplicantIndividual\PersonInfoResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Crypt;

class ContactPersonIResource extends JsonResource
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
            'name' => $this->name,
            'last_name' => $this->last_name,
            'patronymic' => $this->patronymic,
            'position_dok' => $this->position_dok,
            'contact_details' => ContactDetailsResource::collection($this->getDatadetails),
            'note' => $this->note,
        ];
    }

    public function withResponse($request, $response)
    {
        $response->header('Charset', 'utf-8');
        $response->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }
}
