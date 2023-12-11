<?php

namespace App\Http\Resources\Appeal;

use App\Http\Resources\Court\CourtResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CharacterOfQuestionResource extends JsonResource
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
            'character_of_question' => $this->characterOfQuestion->toArray(),
            'court' => new CourtResource($this->court),
            'is_main' => $this->is_main,
        ];
    }
}
