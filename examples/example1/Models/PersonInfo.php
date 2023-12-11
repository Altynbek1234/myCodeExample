<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class PersonInfo extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'last_name', 'patronymic','inn'];

    public function applicantIndividual()
    {
        return $this->belongsTo(ApplicantIndividual::class);
    }
}
