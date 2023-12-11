<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Attachment\Attachable;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class ViolatedRight extends Model
{
    use HasFactory, AsSource, Filterable, Attachable;


    protected $table = 'violated_rights';

    protected $fillable = [
        'case_id',
        'appeal_id',
        'violations_classifier_id',
        'government_agency_id',
        'defendants',
        'note',
    ];

    // Define relationships with other models if needed
    public function case()
    {
        return $this->belongsTo(CaseDeal::class, 'case_id');
    }

    public function appeal()
    {
        return $this->belongsTo(Appeal::class, 'appeal_id');
    }

    public function violationsClassifier()
    {
        return $this->belongsTo(ViolationsClassifier::class, 'violations_classifier_id');
    }

    public function governmentAgency()
    {
        return $this->belongsTo(GovernmentAgency::class, 'government_agency_id');
    }

    public function employeeAction()
    {
        return $this->hasOne(EmployeeAction::class);
    }
}
