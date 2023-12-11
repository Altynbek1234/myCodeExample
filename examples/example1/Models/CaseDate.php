<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseDate extends Model
{
    use HasFactory;

    protected $fillable = [
        'case_id',
        'date',
        'user_id',
    ];
}
