<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Attachment\Attachable;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class EmployeeAction extends Model
{
    use HasFactory, AsSource, Filterable, Attachable;


    protected $table = 'employee_actions';

    protected $fillable = [
        'case_id',
        'appeal_id',
        'government_agency_id',
        'defendants',
        'action_to_violator_id',
        'document_id',
        'date',
        'note',
        'violated_right_id'
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

    public function governmentAgency()
    {
        return $this->belongsTo(GovernmentAgency::class, 'government_agency_id');
    }

    public function actionToViolator()
    {
        return $this->belongsTo(ActionToViolator::class, 'action_to_violator_id');
    }

    public function document()
    {
        return $this->belongsTo(AppealAnswer::class, 'document_id');
    }

    public function violatedRight()
    {
        return $this->belongsTo(ViolatedRight::class);
    }
}
