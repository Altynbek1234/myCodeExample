<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Attachment\Attachable;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class InstitutionsForMonitoring extends Model
{
    use HasFactory, AsSource, Filterable, Attachable;

    public function groupMonitoring()
    {
        return $this->belongsTo(GroupsMonitoring::class, 'groups_monitorings_id');
    }

    public function soate()
    {
        return $this->belongsTo(Soate::class, 'soate_id');
    }

    public function auditRegistries()
    {
        return $this->hasMany(AuditRegistry::class);
    }
}
