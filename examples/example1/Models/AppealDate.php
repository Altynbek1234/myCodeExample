<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppealDate extends Model
{
    use HasFactory;

    protected $fillable = [
        'appeal_id',
        'date',
        'user_id',
    ];
}
