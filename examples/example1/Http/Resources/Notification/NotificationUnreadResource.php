<?php

namespace App\Http\Resources\Notification;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Appeal;
use App\Models\VerbalAppeal;

class NotificationUnreadResource extends JsonResource
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
            'title' => $this->title,
            'message' => $this->message,
            'created_at' => $this->created_at,
            'stage' => $this->getModel()->stage,
            'applicants' => $this->getApplicants(),
            'author' => $this->author->organizationEmployee->getFio(),
        ];
    }

    public function getModel()
    {
        $model = "App\Models\\" .$this->model;
        return $model::find($this->model_id);
    }

    public function getApplicants()
    {
        $model = $this->getModel();
        if ($model instanceof Appeal || $model instanceof VerbalAppeal) {
           $applicants = [];
           foreach ($model->applicantLegalEntities as $key => $applicant) {
               $applicants[$key]['id'] = $applicant->id;
               $applicants[$key]['name_ru'] = $applicant->name;
               $applicants[$key]['name_kg'] = $applicant->name_kg;
               $applicants[$key]['type'] = $applicant->type;
           }
           foreach ($model->applicantIndividuals as $key => $applicant) {
               $applicants[$key]['id'] = $applicant->id;
               $applicants[$key]['name'] = $applicant->personInfo->last_name . ' ' . $applicant->personInfo->name . ' ' . $applicant->personInfo->patronymic;
               $applicants[$key]['type'] = $applicant->type;
           }

           return $applicants;
        }

        return [];
    }

}
