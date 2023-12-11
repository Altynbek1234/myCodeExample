<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Court extends Model
{
    use HasFactory;

    protected $fillable = [
        'government_agency_id',
        'date',
        'zal_number',
        'respondent',
        'plaintiff',
        'stage',
        'defendent_id',
        'applicant_stat_monitoring_id',
        'type_proceeding_id',
        'reason',
    ];

    public function governmentAgency()
    {
        return $this->belongsTo(GovernmentAgency::class);
    }

    public function defendent()
    {
        return $this->belongsTo(Defendant::class);
    }

    public function applicantStatMonitoring()
    {
        return $this->belongsTo(ApplicantStatMonitoring::class, 'applicant_stat_monitoring_id');
    }

    public function typeProceeding()
    {
        return $this->belongsTo(TypeProceeding::class);
    }
}
