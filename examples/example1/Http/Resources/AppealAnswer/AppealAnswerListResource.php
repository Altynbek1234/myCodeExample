<?php

namespace App\Http\Resources\AppealAnswer;

use App\Http\Resources\Appeal\AppealShowResource;
use App\Http\Resources\OrganizationEmployeeResource;
use App\Http\Resources\References\OutgoinglResource;
use App\Http\Resources\References\OutgoingSendingChannelResource;
use Illuminate\Http\Resources\Json\JsonResource;

class AppealAnswerListResource extends JsonResource
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
            'appeal' => new AppealShowResource($this->appeal),
            'issued_number' => $this->issued_number,
            'doc_type' => new OutgoinglResource($this->docType),
            'outgoing_sending_channel' => new OutgoingSendingChannelResource($this->outgoingSendingChannel),
            'applicants' => json_decode($this->applicant),
            'persons' => json_decode($this->person),
            'organization' => json_decode($this->organization),
            'issued_date' => $this->issued_date,
            'executor' => new OrganizationEmployeeResource($this->executor),
            'summary' => $this->summary,
            'sent_date' => $this->sent_date,
            'doc_link' => $this->doc_link,
        ];
    }
}
