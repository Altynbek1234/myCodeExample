<?php

namespace App\Http\Resources\OrganizationResources;

use App\Http\Resources\ApplicantIndividual\PersonInfoResource;
use App\Http\Resources\ContactDetailsResource;
use App\Models\Defendant;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Crypt;
use App\Http\Resources\References\DefendantResource;

class OrganizationResource extends JsonResource
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
            'name_ru' => $this->name_ru,
            'defendents' => DefendantResource::collection($this->getAttachedOnThisAppealDefendants()),
        ];
    }

    public function withResponse($request, $response)
    {
        $response->header('Charset', 'utf-8');
        $response->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

    public function getAttachedOnThisAppealDefendants()
    {
        $defendants = Defendant::whereIn(json_decode($this->pivot->defendent_ids, true))->get();

        return $defendants;
    }
}
