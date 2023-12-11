<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'link',
        'file',
        'comment',
        'appeal_id',
        'type_of_document_id',
        'appeal_answer_id',
        'case_id',
        'audit_registry_id'
    ];

    public function appeal()
    {
        return $this->belongsTo(Appeal::class);
    }

    public function getLinkAttribute($value)
    {
        $value = str_replace("http://ak.at.kg/", "", $value);
        if (strpos($value, config('services.nextcloud.url')) !== false) {
            return $value;
        }
        return config('services.nextcloud.url') . $value;
    }

    public function typeOfDocument()
    {
        return $this->belongsTo(TypeOfDocument::class);
    }

    public function appealAnswer()
    {
        return $this->belongsTo(AppealAnswer::class);
    }

    public function case()
    {
        return $this->belongsTo(CaseDeal::class);
    }

    public function auditRegistry()
    {
        return $this->belongsTo(AuditRegistry::class);
    }
}
