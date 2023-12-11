<?php

namespace App\Http\Resources\Stage;

use Illuminate\Http\Resources\Json\JsonResource;

class StageHistoryResource extends JsonResource
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
           'stage' => new StageResource($this->stage),
           'date' => $this->date,
           'comment' => $this->comment,
           'user' => $this->user,
           'organizationEmployee' => $this->user->organizationEmployee(),
           'fields_history' => json_decode($this->fields_history) ?? []
        ];
    }
}
