<?php

namespace App\Http\Resources\Stage;

use Illuminate\Http\Resources\Json\JsonResource;

class StageResource extends JsonResource
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
            'name_ru' => $this->name_ru,
            'name_kg' => $this->name_kg,
            'id' => $this->id,
        ];
    }
}
