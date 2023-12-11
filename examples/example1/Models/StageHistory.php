<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StageHistory extends Model
{
    use HasFactory;

    const START_STAGE_ID = 1;
    const START_STAGE_VERBAL_ID = 21;
    const START_STAGE_APPEAL_ANSWER_ID = 23;
    const START_STAGE_CASE_ID = 25;
    const START_STAGE_AUDIT_REGISTRY_ID = 29;


    protected $fillable = [
        'appeal_id',
        'stage_id',
        'user_id',
        'comment',
        'is_active',
        'verbal_appeal_id',
        'prev_stage_id',
        'fields_history',
        'action_id',
        'vhod_number',
        'resolution',
        'government_agency_id',
        'prev_stage_history_id',
        'appeal_answer_id',
        'case_deal_id',
        'audit_registry_id',
        'date'
    ];


    public function appeal()
    {
        return $this->belongsTo(Appeal::class);
    }

    public function verbalAppeal()
    {
        return $this->belongsTo(VerbalAppeal::class);
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

    public function stage()
    {
        return $this->belongsTo(Stage::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
