<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Attachment\Attachable;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

//Вич-статус
class HIVStatus extends Model
{
    use HasFactory, AsSource, Filterable, Attachable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'hiv_statuses';

    /**
     * The fields abble to change.
     *
     * @var arr
     */
    protected $fillable = ['name_ru', 'name_kg', 'status_id'];
}
