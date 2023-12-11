<?php
namespace App\Http\Resources\References;

use App\Http\Resources\ApplicantIndividual\PersonInfoResource;
use App\Models\Defendant;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\GovernmentAgency;
use Illuminate\Support\Facades\Crypt;

class GovernmentAgencyForAppealResource extends JsonResource
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
            'name_kg' => $this->name_kg,
            'code' => $this->code,
            'parents' => $this->getParents(),
        ];
    }

    public function withResponse($request, $response)
    {
        $response->header('Charset', 'utf-8');
        $response->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

    public function getParents()
    {
        $parents = [];
        $parent = $this->parent;
        while ($parent) {
            $parents[] = [
                'id' => $parent->id,
                'name_ru' => $parent->name_ru,
                'name_kg' => $parent->name_kg,
                'code' => $parent->code,
            ];
            $parent = $parent->parent;
        }
        return $parents;
    }
}
