<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Attachment\Attachable;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

//Уровень образования
class LevelOfEducation extends Model
{
    use HasFactory, AsSource, Filterable, Attachable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'level_of_education';

    /**
     * The fields abble to change.
     *
     * @var arr
     */
    protected $fillable = ['name_ru', 'name_kg', 'code',  'status_id'];
}
