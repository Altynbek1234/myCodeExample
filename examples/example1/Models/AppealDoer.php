<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppealDoer extends Model
{
    use HasFactory;

    protected $fillable = [
        'appeal_id',
        'doers',
        'user_id',
    ];
}
