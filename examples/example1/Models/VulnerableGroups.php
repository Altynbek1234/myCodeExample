<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Attachment\Attachable;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class VulnerableGroups extends Model
{
    use HasFactory, AsSource, Filterable, Attachable;

    /**
     * The fields abble to change.
     *
     * @var arr
     */
    protected $fillable = ['name_ru', 'code', 'name_kg', 'status_id'];
}
