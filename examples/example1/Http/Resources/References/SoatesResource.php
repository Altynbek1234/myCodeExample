<?php

namespace App\Http\Resources\References;

use App\Http\Resources\ApplicantIndividual\PersonInfoResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Crypt;

class SoatesResource extends JsonResource
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
            'parent_id' => $this->parent_id,
            'settlement_type_id' => $this->settlement_type_id,
            'code' => $this->code,
            'level' => $this->level,
            'number_position' => $this->number_position,
            'name' => $this->name,
            'name_kg' => $this->name_kg,
            'name_en' => $this->name_en,
            'status_id' => $this->status_id,
            'created_at' => $this->created_at->format('d M Y, H:i a'),
        ];
    }

    public function withResponse($request, $response)
    {
        $response->header('Charset', 'utf-8');
        $response->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }
}
