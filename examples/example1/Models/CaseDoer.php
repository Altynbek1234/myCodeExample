<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseDoer extends Model
{
    use HasFactory;

    protected $fillable = [
        'case_id',
        'doers',
        'user_id',
    ];
}
