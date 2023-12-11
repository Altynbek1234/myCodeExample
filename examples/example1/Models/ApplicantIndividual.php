<?php

namespace App\Models;

use App\Interfaces\ModelInterfaces\DocumentRelationsInterface;
use App\Services\NextCloudService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ApplicantIndividual extends Model implements DocumentRelationsInterface
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function personInfo()
    {
        return $this->belongsTo(PersonInfo::class);
    }

    public function getDirectory(): string
    {
        return 'individuals/' . Str::slug($this->personInfo->name) . '_' . $this->id . '/';
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function shareDocument($filename)
    {
        $service = new NextCloudService();
        $link = $service->shareFile($filename, 'beka');

        return $link;
    }

    public function getTypeAttribute()
    {
        return 'individual';
    }

    public function contactPersons() {
        return $this->hasMany(ContactPerson::class,'applicant_indiv_id');
    }

    public function getNumber() {
        $selfContact = $this->contactPersons()->where('applicant_indiv_id',$this->id)->first();
        if (!$selfContact) {
            return null;
        }
        $contactDetails = $selfContact->getDatadetails()->where('type_id', 1)->first();
        if (!$contactDetails) {
            return null;
        }
        $number = $selfContact->getDatadetails()->where('type_id', 1)->first()->value;

        return $number;
    }

    public function profile()
    {
        return $this->hasOne(ProfilePersonData::class, 'a_individual_id');
    }

    public function verbalAppeals()
    {
        return $this->belongsToMany(VerbalAppeal::class);
    }

    public function status()
    {
        return $this->belongsTo(ApplicantStatus::class, 'status_id');
    }

    public function gender()
    {
        return $this->belongsTo(Gender::class, 'gender_id');
    }

    public function citizenship()
    {
        return $this->belongsTo(Citizenship::class, 'citizenship_id');
    }
}
