<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Orchid\Platform\Models\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'permissions',
        'stages_permissions_user',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'permissions',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'permissions'          => 'array',
        'stages_permissions_user' => 'array',
        'email_verified_at'    => 'datetime',
        'notification_settings' => 'array',
    ];

    /**
     * The attributes for which you can use filters in url.
     *
     * @var array
     */
    protected $allowedFilters = [
        'id',
        'name',
        'email',
        'permissions',
    ];

    /**
     * The attributes for which can use sort in url.
     *
     * @var array
     */
    protected $allowedSorts = [
        'id',
        'name',
        'email',
        'updated_at',
        'created_at',
    ];

    public function organizationEmployee()
    {
        return $this->hasOne(OrganizationEmployees::class, 'user_id');
    }

    public function getAvailableStagePermissions($stage)
    {
        $roles = $this->getRoles();
        $availablePermissions = [];
        $permissions = [];
        foreach($roles as $role) {
            $permissions[] = json_decode($role->stages_permissions_role, true) ?? [];
        }
        $permissions[] = $this->stages_permissions_user ?? [];
        foreach ($permissions as $permission) {
            foreach ($permission as $stageActionsIds => $value) {
                if ($value == 1) {
                    $stageActionsIds = explode('.', $stageActionsIds);
                    $stageId = $stageActionsIds[0];
                    $actionId = $stageActionsIds[1];
                    $availablePermissions[$stageId][] = $actionId;
                }
            }
        }

        return !empty($availablePermissions[$stage]) ? $availablePermissions[$stage] : [];
    }

    public function getAllAvailablePermissions()
    {
        $roles = $this->getRoles();
        $availablePermissions = [];
        $permissions = [];
        foreach($roles as $role) {
            $permissions[] = json_decode($role->stages_permissions_role, true) ?? [];
        }
        $permissions[] = $this->stages_permissions_user ?? [];

        foreach ($permissions as $permission) {
            foreach ($permission as $stageActionsIds => $value) {
                if ($value == 1) {
                    $stageActionsIds = explode('.', $stageActionsIds);
                    $stageId = $stageActionsIds[0];
                    $actionId = $stageActionsIds[1];
                    $availablePermissions[$stageId][] = $actionId;
                }
            }
        }

        return $availablePermissions;
    }

    public function getStagesPermittedForView()
    {
        foreach ($this->getAllAvailablePermissions() as $stageId => $actions) {
            if (in_array(StageAction::VIEW_STAGE_ACTION_ID, $actions)) {
                $stages[] = $stageId;
            }
        }

        return $stages ?? [];
    }

    public function getUserRoles()
    {
        return $this->belongsToMany(Role::class, 'role_users', 'user_id', 'role_id');
    }

    public static function getPermittedUsers($appeal) //Получение пользователей, у которых есть разрешение, кроме просмотра и отмены
    {
        $regionId = $appeal->representativeIo->id;
        $users = User::where(function ($query) use ($appeal) {
            $roles = Role::getPermittedRoles($appeal);
            $roleIds = [];

            foreach ($roles as $role) {
                $roleIds[] = $role->id;
            }
            $regionId = $appeal->representativeIo->id;
            $query->where('stages_permissions_user', 'LIKE', '%"' . $appeal->stage->id . '.%.' . $regionId . '":"1"%')
                ->orWhere('stages_permissions_user', 'LIKE', '%"' . $appeal->stage->id . '.%":"1"%')
            ->orWhereHas('getUserRoles', function ($q) use ($roleIds) {
                $q->whereIn('id', $roleIds);
            });
        })->get();

        $result = [];
        foreach ($users as $user) {
            $stagePermissionsUser = $user->stages_permissions_user;
            foreach ($stagePermissionsUser as $key => $permission) {
                if ($permission == 1) {
                    $data = explode('.', $key);
                    $stageActionId = $data[1];
                    $permissionRegionId = isset($data[2]) ?? null;

                    if ($permissionRegionId
                        && $permissionRegionId == $regionId
                        && !in_array($stageActionId, StageAction::BASE_STAGE_ACTIONS)) {
                        $result[$user->id] = $user;
                    } else if($permissionRegionId == null && !in_array($stageActionId, StageAction::BASE_STAGE_ACTIONS)) {
                        $result[$user->id] = $user;
                    }
                }
            }
        }
        return $result;
    }

    public function getStages($representativeIOId)
    {
        $roles = $this->getRoles();
        $availableStages = [];
        $permissions = [];
        foreach($roles as $role) {
            $permissions[] = json_decode($role->stages_permissions_role, true) ?? [];
        }
        $permissions[] = $this->stages_permissions_user ?? [];
        foreach ($permissions as $permission) {
            foreach ($permission as $stageActionsIds => $value) {
                $stageActionsIds = explode('.', $stageActionsIds);
                $stageId = $stageActionsIds[0];
                $actionId = $stageActionsIds[1];
                $regionId = $stageActionsIds[2] ?? null;
                if ($value == 1 && ($regionId == null && in_array($actionId, StageAction::BASE_STAGE_ACTIONS)) || ( $regionId != null && $regionId == $representativeIOId)) {
                    $availableStages[] = $stageId;
                }
            }
        }

        return $availableStages;
    }

    public static function getLeaders()
    {
        return self::whereHas('getUserRoles', function($query) {
            $query->where('id', Role::LEADER_ID);
        })->get();
    }
}
