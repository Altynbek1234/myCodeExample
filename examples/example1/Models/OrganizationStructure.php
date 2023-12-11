<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Attachment\Attachable;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class OrganizationStructure extends Model
{
    use HasFactory, AsSource, Filterable, Attachable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'organization_structure';

    protected $fillable = [
        'name_ru',
        'name_kg',
        'status_id',
        'address',
        'phone',
        'fax',
        'email',
        'parent_id',
        'representatives_io_id',
    ];

    // Связь один-ко-многим с моделью сотрудников
    public function employees()
    {
        return $this->hasMany(OrganizationEmployees::class);
    }

    public function children()
    {
        return $this->hasMany(OrganizationStructure::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(OrganizationStructure::class, 'parent_id');
    }

    public function representativeIo()
    {
        return $this->belongsTo(RepresentativeIO::class, 'representatives_io_id');
    }

    public function categoriesOfDepartmentRequests()
    {
        return $this->hasMany(CategoriesOfDepartmentRequests::class, 'department_id');
    }
}
