<?php

namespace App\Http\Resources\ApplicantIndividual;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Crypt;


class PersonInfoResource extends JsonResource
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
            'name' => $this->name,
            'last_name' => $this->last_name,
            'patronymic' => $this->patronymic,
            'inn' => $this->inn,
        ];
//        return [
//            'id' => $this->id,
//            'name' => $this->name ? Crypt::decryptString($this->name) : '',
//            'last_name' => $this->last_name ? Crypt::decryptString($this->last_name) : '',
//            'patronymic' => $this->patronymic ? Crypt::decryptString($this->patronymic) : '',
//            'inn' => $this->inn ? Crypt::decryptString($this->inn) : '',
//        ];
    }

    public function withResponse($request, $response)
    {
        $response->header('Charset', 'utf-8');
        $response->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }
}
