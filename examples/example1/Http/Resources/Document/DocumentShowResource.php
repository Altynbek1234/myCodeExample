<?php

namespace App\Http\Resources\Document;

use App\Http\Resources\ApplicantIndividual\ItemAIResource;
use Illuminate\Http\Resources\Json\JsonResource;

class DocumentShowResource extends JsonResource
{
/**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'number' => $this->number,
            'name' => $this->name,
            'issued_by' => $this->issued_by,
            'date_of_issued' => $this->date_of_issued,
            'expiry_date' => $this->expiry_date,
            'type_of_document' => $this->typeOfDocument,
            'comment' => $this->comment,
            'applicant_individual' => $this->applicantIndividual,
            'applicant_legal_entity' => $this->applicantLegalEntity,
            'appeal' => $this->appeal,
            'case' => $this->case,
            'appeal_answer' => $this->appealAnswer,
            'file' => $this->getLink(),
            'status' => $this->status
        ];
    }

    public function getLink()
    {
        $explodedFileName = explode('/' ,$this->file);

        return $this->link . '/download/' . end($explodedFileName);
    }
}
