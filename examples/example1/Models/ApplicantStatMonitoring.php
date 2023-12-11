<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class ApplicantStatMonitoring extends Model
{
    use HasFactory, asSource;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'applicant_stat_monitorings';

    protected $fillable = [
        'name_ru',
        'name_kg',
    ];
}
