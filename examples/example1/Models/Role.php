<?php

namespace App\Models;

use Orchid\Platform\Models\Role as OrchidRole;

class Role extends OrchidRole
{

    public const LEADER_ID = 1;

    /**
     * @var array
     */
    protected $casts = [
        'permissions' => 'array',
        'stages_permissions_role' => 'array',
    ];

    public static function getPermittedRoles($appeal) //Получение ролей, у которых есть разрешение, кроме просмотра и отмены
    {
        $regionId = $appeal->representativeIo->id;
        $roles = Role::where(function ($query) use ($appeal, $regionId) {
            $query->where('stages_permissions_role', 'LIKE', '%"' . $appeal->stage->id . '.%.' . $regionId . '":"1"%')
                ->orWhere('stages_permissions_role', 'LIKE', '%"' . $appeal->stage->id . '.%":"1"%');
        })->get();
        $result = [];
        foreach ($roles as $role) {
            $stagePermissionsRole = $role->stages_permissions_role;
            foreach ($stagePermissionsRole as $key => $permission) {
                if ($permission == 1) {
                    $data = explode('.', $key);
                    $stageActionId = $data[1];
                    $permissionRegionId = isset($data[2]) ?? null;

                    if ($permissionRegionId
                        && $permissionRegionId == $regionId
                        && !in_array($stageActionId, StageAction::BASE_STAGE_ACTIONS)) {
                        $result[$role->id] = $role;
                    } else if($permissionRegionId == null && !in_array($stageActionId, StageAction::BASE_STAGE_ACTIONS)) {
                        $result[$role->id] = $role;
                    }
                }
            }
        }
        return $result;
    }
}
