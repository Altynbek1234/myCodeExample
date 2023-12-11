<?php

namespace App\Http\Resources\AppealAnswer;


use App\Http\Resources\Appeal\AppealShowResource;
use App\Http\Resources\File\FileShowResource;
use App\Http\Resources\OrganizationEmployeeResource;
use App\Http\Resources\References\OutgoinglResource;
use App\Http\Resources\References\OutgoingSendingChannelResource;
use App\Http\Resources\References\TypeSpecializedInstitutionResource;
use App\Http\Resources\Stage\StageHistoryResource;
use App\Http\Resources\Stage\StageResource;
use Illuminate\Http\Resources\Json\JsonResource;

class AppealAnswerShowResource extends JsonResource
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
            'available_actions' => $this->getAvailableActions(),
            'stage_histories' => StageHistoryResource::collection($this->stageHistories),
            'appeal' => new AppealShowResource($this->appeal),
            'applicant' => json_decode($this->applicant),
            'person' => json_decode($this->person),
            'issued_date' => $this->issued_date,
            'issued_number' => $this->issued_number,
            'summary' => $this->summary,
            'sent_date' => $this->sent_date,
            'outgoing_sending_channel' => new OutgoingSendingChannelResource($this->outgoingSendingChannel),
            'executor' => new OrganizationEmployeeResource($this->executor) ,
            'organization' => json_decode($this->organization),
            'doc_type' => new OutgoinglResource($this->docType),
            'stage' => new StageResource($this->stage),
            'documents' => FileShowResource::collection($this->documents),
            'whom_id' => $this->whom_id,
            'institution' => new TypeSpecializedInstitutionResource($this->institution),
            'another_addressee' => $this->another_addressee
        ];
    }
}
