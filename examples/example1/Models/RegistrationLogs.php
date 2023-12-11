<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Attachment\Attachable;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class RegistrationLogs extends Model
{
    use HasFactory, AsSource, Filterable, Attachable;

    protected $fillable = [
        'name_ru', 'name_kg', 'status_id',
        'direction_document_id', 'initial_number', 'format',
        'registration_start_date', 'code', 'annual_update'
    ];

    public function derectionDocument($id)
    {
        $directionDocument = DirectionDocument::find($id);
        return $directionDocument->name_ru;;
    }
}
