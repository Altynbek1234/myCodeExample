<?php

namespace App\Http\Resources\Court;

use App\Http\Resources\References\ApplicantStatMonitoringResource;
use App\Http\Resources\References\DefendantResource;
use App\Http\Resources\References\OrganizationComplaintResource;
use App\Http\Resources\References\TypeProceedingResource;
use App\Models\TypeProceeding;
use Illuminate\Http\Resources\Json\JsonResource;

class CourtResource extends JsonResource
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
            'government_agency' => new OrganizationComplaintResource($this->governmentAgency),
            'date' => $this->date,
            'zal_number' => $this->zal_number,
            'respondent' => $this->respondent,
            'plaintiff' => $this->plaintiff,
            'stage' => $this->stage,
            'defendent' => new DefendantResource($this->defendent),
            'applicant_stat_monitoring' => new ApplicantStatMonitoringResource($this->applicantStatMonitoring),
            'type_proceeding' => new TypeProceedingResource($this->typeProceeding),
            'reason' =>  $this->reason,
        ];
    }
}
