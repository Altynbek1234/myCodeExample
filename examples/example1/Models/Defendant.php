<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Orchid\Attachment\Attachable;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Defendant extends Model
{
    use HasFactory, AsSource, Filterable, Attachable;

    protected $table = 'defendents';

    protected $fillable = [
        'name',
        'last_name',
        'patronymic',
        'inn',
        'born_date',
        'gender_id',
        'government_agency_id',
        'position_governmental_id'
    ];

    public function organizationComplaint()
    {
        return $this->belongsTo(GovernmentAgency::class);
    }

    public function gender()
    {
        return $this->belongsTo(Gender::class);
    }

    public function organizationData()
    {
        return $this->belongsTo(GovernmentAgency::class, 'government_agency_id');
    }

    public function positionGovernmental()
    {
        return $this->belongsTo(PositionGovernmental::class, 'position_governmental_id');
    }

}
