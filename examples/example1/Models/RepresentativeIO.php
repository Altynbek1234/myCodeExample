<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Attachment\Attachable;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

//Таблица “Представительства ИО КР
class RepresentativeIO extends Model
{
    use HasFactory, AsSource, Filterable, Attachable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'representatives_io';

    /**
     * The fields abble to change.
     *
     * @var arr
     */
    protected $fillable = [
        'name_ru', 'name_kg', 'status_id',
        'adress', 'phone', 'fax', 'email', 'code'
    ];

    public function organizationEmployees()
    {
        return $this->hasMany(OrganizationEmployees::class, 'representatives_io_id');
    }

    public function verbalAppeals()
    {
        return $this->hasManyThrough(VerbalAppeal::class, OrganizationEmployees::class, 'representatives_io_id', 'employee_id');
    }

    public function organizationStructures()
    {
        return $this->hasMany(OrganizationStructure::class, 'representatives_io_id');
    }

    public function cases()
    {
        return $this->hasMany(CaseDeal::class,'representative_io_id');
    }
}
