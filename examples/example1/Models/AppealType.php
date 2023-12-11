<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class AppealType extends Model
{
    use HasFactory, asSource;

    protected $table = 'appeal_types';

    protected $fillable = [
        'name_ru',
        'name_kg',
    ];
}
