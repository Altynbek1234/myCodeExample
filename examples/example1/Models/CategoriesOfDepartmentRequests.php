<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Attachment\Attachable;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class CategoriesOfDepartmentRequests extends Model
{
    use HasFactory, AsSource, Filterable, Attachable;

    /**
     * The fields abble to change.
     *
     * @var arr
     */
    protected $fillable = ['name_ru', 'name_kg', 'status_id', 'parent_id', 'code'];

    public function department()
    {
       return $this->belongsTo(OrganizationStructure::class, 'department_id');
    }
}

