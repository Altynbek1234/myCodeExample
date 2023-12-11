<?php
namespace App\Http\Resources\Case;

use App\Http\Resources\Appeal\AppealListResource;
use App\Http\Resources\ApplicantIndividual\ItemAIResource;
use App\Http\Resources\ApplicantLegalEntity\ItemALEResource;
use App\Http\Resources\File\FileShowResource;
use App\Http\Resources\OrganizationEmployeeResource;
use App\Http\Resources\ProfilePersonData\ProfilePersonDataResource;
use App\Http\Resources\References\ActionsOfAkyykatchyResource;
use App\Http\Resources\Stage\StageHistoryResource;
use App\Http\Resources\Stage\StageResource;
use App\Models\ActionsOfAkyykatchy;
use App\Models\Appeal;
use App\Models\CaseDeal;
use App\Models\Defendant;
use App\Models\OrganizationEmployee;
use App\Models\PositionGovernmental;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class CaseActionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */

    public function toArray($request)
    {
        $executorIds = json_decode($this->executors);
        $executors = OrganizationEmployee::whereIn('id', $executorIds)->get();
        $action = ActionsOfAkyykatchy::findOrFail($this->action_id);
        return [
            'id' => $this->id,
            'action' => new ActionsOfAkyykatchyResource($action),
            'date' => $this->date,
            'description' => $this->description,
            'place' => $this->place,
            'executors' => OrganizationEmployeeResource::collection($executors)
        ];
    }
}

