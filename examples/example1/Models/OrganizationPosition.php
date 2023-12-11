<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Attachment\Attachable;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class OrganizationPosition extends Model
{
    use HasFactory, AsSource, Filterable, Attachable;

    protected $table = 'organization_position';

    protected $fillable = ['name_ru', 'name_kg', 'status_id'];

    // Связь один-ко-многим с моделью сотрудников
    public function employees()
    {
        return $this->hasMany(OrganizationEmployees::class);
    }

    // Связь один-ко-многим с моделью статусов
    public function statusReferences()
    {
        return $this->belongsTo(StatusReference::class);
    }
}
