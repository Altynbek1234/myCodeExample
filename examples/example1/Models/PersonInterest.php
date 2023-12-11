<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonInterest extends Model
{
    use HasFactory;

    public function personInfo()
    {
        return $this->belongsTo(PersonInfo::class);
    }
}
