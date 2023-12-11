<?php

namespace App\Http\Resources\File;

use Illuminate\Http\Resources\Json\JsonResource;

class FileShowResource extends JsonResource
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
            'type_of_document' => $this->typeOfDocument,
            'comment' => $this->comment,
            'file' => $this->getLink(),
            'nextcloud_link' => $this->link,
        ];
    }

    public function getLink()
    {
        $explodedFileName = explode('/' ,$this->file);

        return $this->link . '/download/' . end($explodedFileName);
    }
}
