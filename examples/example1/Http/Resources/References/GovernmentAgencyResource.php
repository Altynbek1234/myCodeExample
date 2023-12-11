<?php
namespace App\Http\Resources\References;

use App\Http\Resources\ApplicantIndividual\PersonInfoResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\GovernmentAgency;
use Illuminate\Support\Facades\Crypt;

class GovernmentAgencyResource extends JsonResource
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
            'parent_id' => $this->parent_id,
            'children' => $this->whenLoaded('children', function () {
                return $this->children->isEmpty() ? null : self::collection($this->children);
            }),
            'government_agencies' => $this->whenLoaded('governmentAgencies', function () {
                if ($this->governmentAgencies->isLoaded()) {
                    return GovernmentAgencyResource::collection($this->governmentAgencies);
                } else {
                    return $this->governmentAgencies;
                }
            }),
        ];
//        return [
//            'id' => $this->id,
//            'name_ru' => $this->name_ru,
//            'name_kg' => $this->name_kg,
//            'parent_id' => $this->parent_id,
//            'code' => $this->code,
//            'status_id' => $this->status_id,
//            'created_at' => $this->created_at->format('d M Y, H:i a'),
//        ];
    }

    public function withResponse($request, $response)
    {
        $response->header('Charset', 'utf-8');
        $response->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

    public static function collection($resource)
    {
        return parent::collection($resource->load('children'));
    }
}
