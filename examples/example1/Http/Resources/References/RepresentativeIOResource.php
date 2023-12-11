<?php

namespace App\Http\Resources\References;

use Illuminate\Http\Resources\Json\JsonResource;

class RepresentativeIOResource extends JsonResource
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
            'name_ru' => $this->name_ru,
            'name_kg' => $this->name_kg,
            'adress' => $this->adress,
            'phone' => $this->phone,
            'fax' => $this->fax,
            'email' => $this->email,
            'code' => $this->code,
            'status_id' => $this->status_id,
        ];

    }
}
