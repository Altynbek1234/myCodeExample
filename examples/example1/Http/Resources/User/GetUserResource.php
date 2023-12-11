<?php

namespace App\Http\Resources\User;

use App\Http\Resources\OrganizationEmployeeResource;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class GetUserResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'organization_employee' => new OrganizationEmployeeResource($this->organizationEmployee),
            'notification_settings' => $this->notification_settings ?? []
        ];
    }
}
