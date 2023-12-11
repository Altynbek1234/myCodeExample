<?php

namespace App\Http\Resources\AuditRegistry;

use App\Http\Resources\Case\CaseShowResource;
use App\Http\Resources\File\FileShowResource;
use App\Http\Resources\References\DefendantResource;
use App\Http\Resources\References\DetectedViolationResource;
use App\Http\Resources\References\ImpactMeasuresTakenResource;
use App\Http\Resources\References\InstitutionsForMonitoringResource;
use App\Http\Resources\References\OrganizationPositionResource;
use App\Http\Resources\References\PositionGovernmentalResource;
use App\Http\Resources\References\TypesOfInspectionResource;
use App\Http\Resources\Stage\StageHistoryResource;
use App\Http\Resources\Stage\StageResource;
use Illuminate\Http\Resources\Json\JsonResource;

class AuditRegistryResource extends JsonResource
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
            'type_of_inspection' => new TypesOfInspectionResource($this->typesOfInspection),
            'date_check' => $this->date_check,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'institution_for_monitoring' => new InstitutionsForMonitoringResource($this->institutionForMonitoring),
            'basis_of_verification' => $this->basis_of_verification,
            'appeal_id' => $this->appeal_id,
            'surname_of_responsible' => $this->surname_of_responsible,
            'name_of_responsible' => $this->name_of_responsible,
            'middle_name' => $this->middle_name,
            'contact_number' => $this->contact_number,
            'document' => $this->document,
            'inspection_result_id' => $this->inspection_result_id,
            'conclusions' => $this->conclusions,
            'position_governmental' => new PositionGovernmentalResource($this->positionGovernmental),
            'organization_employees' =>  $this->organizationEmployees,
            'detected_violations' => DetectedViolationResource::collection($this->detectedViolations),
            'case_deals' => CaseShowResource::collection($this->caseDeals),
            'impact_measures_taken' => ImpactMeasuresTakenResource::collection($this->impactMeasuresTaken),
            'defendant' => DefendantResource::collection($this->defendant),
            'note' => $this->note,
            'available_actions' => $this->getAvailableActions(),
            'stage_histories' => StageHistoryResource::collection($this->stageHistories),
            'stage' => new StageResource($this->stage),
            'number' => $this->number,
            'documents' => FileShowResource::collection($this->documents),

        ];
    }

    public function withResponse($request, $response)
    {
        $response->header('Charset', 'utf-8');
        $response->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }
}
