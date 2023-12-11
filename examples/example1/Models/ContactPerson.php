<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactPerson extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function applicantLE()
    {
        return $this->hasOne(ApplicantLegalEntity::class, 'id');
    }

    public function applicantIndiv()
    {
        return $this->hasOne(ApplicantIndividual::class, 'id');
    }

    public function getDatadetails()
    {
        return $this->hasMany(ContactDetails::class);
    }

}
