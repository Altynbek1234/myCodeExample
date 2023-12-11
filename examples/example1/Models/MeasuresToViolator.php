<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Attachment\Attachable;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class MeasuresToViolator extends Model
{
    use HasFactory, AsSource, Filterable, Attachable;

    protected $table = 'measures_to_violator';

    protected $fillable = [
        'case_id',
        'reference_employee_action_id',
        'document_id',
        'note',
    ];

    public function case()
    {
        return $this->belongsTo(CaseDeal::class, 'case_id');
    }

    public function action()
    {
        return $this->belongsTo(ActionToViolator::class, 'action_id');
    }

    public function document()
    {
        return $this->belongsTo(Document::class, 'document_id');
    }

}
