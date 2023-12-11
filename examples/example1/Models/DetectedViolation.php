<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Attachment\Attachable;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class DetectedViolation extends Model
{
    use HasFactory, AsSource, Filterable, Attachable;

    public function auditRegistries()
    {
        return $this->belongsToMany(AuditRegistry::class, 'audit_registry_detected_violation', 'detected_violation_id', 'audit_registry_id');
    }
}
