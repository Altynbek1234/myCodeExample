<?php
namespace App\Http\Resources\MeasuresToViolator;

use Illuminate\Http\Resources\Json\JsonResource;

class MeasuresToViolatorShowResource extends JsonResource
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
            'case_id' => $this->case_id,
            'reference_employee_action_id' => $this->reference_employee_action_id,
            'document_id' => $this->document_id,
            'note' => $this->note
        ];
    }
}
