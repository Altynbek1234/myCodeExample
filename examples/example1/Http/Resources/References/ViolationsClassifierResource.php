<?php

namespace App\Http\Resources\References;

use Illuminate\Http\Resources\Json\JsonResource;

class ViolationsClassifierResource extends JsonResource
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
            'created_at' => $this->created_at->format('d M Y, H:i a'),
            'parent_id' => $this->parent_id,
            'children' => $this->whenLoaded('children', function () {
                return $this->children->isEmpty() ? null : self::collection($this->children);
            }),
            'violations_classifier' => $this->whenLoaded('violationClassifier', function () {
                if ($this->violationClassifier->isLoaded()) {
                    return ViolationsClassifierResource::collection($this->violationClassifier);
                } else {
                    return $this->violationClassifier;
                }
            }),
        ];
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
