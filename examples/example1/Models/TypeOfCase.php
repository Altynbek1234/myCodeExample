<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Attachment\Attachable;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class TypeOfCase extends Model
{
    use HasFactory, AsSource, Filterable, Attachable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'type_of_cases';

    /**
     * The fields abble to change.
     *
     * @var arr
     */
    protected $fillable = ['name_ru', 'reference_types_of_cases', 'code', 'name_kg', 'status_id'];
}
