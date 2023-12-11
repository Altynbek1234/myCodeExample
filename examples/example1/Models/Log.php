<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    protected $fillable = [
        'access_status_id',
        'changed_permissions',
        'user_id',
        'changed_user_id',
        'changed_role_id',
    ];

    public function accessStatus()
    {
        return $this->belongsTo(AccessStatus::class);
    }

    public function getAttachedPermissions()
    {
//        permissions
        $changedPermissions = $this->changed_permissions;
        $permissions = [];
        $i = 1;
        if ($changedPermissions == null) {
            return '';
        }
        foreach ($changedPermissions->attached as $permission) {
            $permissions[] = $i . ')' . $this->getPermissionLogText($permission);
            $i++;
        }
//        roles
        foreach ($changedPermissions->attachedRoles as $role) {
            $role = Role::find($role);
            $permissions[] = $i . ')' . $role->name . ' (роль)';
            $i++;
        }

        return implode(', <br/>', $permissions);
    }

    public function getDetachedPermissions()
    {
//        permissions
        $changedPermissions = $this->changed_permissions;
        $permissions = [];
        $i = 1;
        if ($changedPermissions == null) {
            return '';
        }
        foreach ($changedPermissions->detached as $permission) {
            $permissions[] = $i . ')' . $this->getPermissionLogText($permission);
            $i++;
        }
//        roles
        foreach ($changedPermissions->detachedRoles as $role) {
            $role = Role::find($role);
            $permissions[] = $i . ')' . $role->name . ' (роль)';
            $i++;
        }

        return implode(',<br/>', $permissions);
    }

    public function getPermissionLogText($permission)
    {
        $ids = explode('.', $permission);
        $stageId = $ids[0];
        $actionId = $ids[1];
        $regionId = $ids[2] ?? null;
        $text = StageAction::find($actionId)->name;
        if($stageId) {
            $text .= ' на стадии "' . Stage::find($stageId)->name_ru . '"';
        }
        if ($regionId) {
            $text .= ' (' . RepresentativeIO::find($regionId)->name_ru . ')';
        }

        return $text;
    }

    public function getChangedPermissionsAttribute($value)
    {
        return json_decode($value);
    }

    public function setChangedPermissionsAttribute($value)
    {
        $this->attributes['changed_permissions'] = json_encode($value);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function changedUser()
    {
        return $this->belongsTo(User::class, 'changed_user_id');
    }

    public function changedRole()
    {
        return $this->belongsTo(Role::class, 'changed_role_id');
    }
}
