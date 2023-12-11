<?php

namespace App\Models;

use App\Services\NextCloudService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Redis;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'issued_by',
        'date_of_issued',
        'expiry_date',
        'file',
        'another_type',
        'type_of_document_id',
        'applicant_individual_id',
        'applicant_legal_entity_id',
        'comment',
        'link',
        'status',
        'name',
    ];

    public function getLinkAttribute($value)
    {
        $value = str_replace("http://ak.at.kg/", "", $value);
        if (strpos($value, config('services.nextcloud.url')) !== false) {
            return $value;
        }
        return config('services.nextcloud.url') . $value;
    }


    public function typeOfDocument()
    {
        return $this->belongsTo(TypeOfDocument::class);
    }

    public function applicantIndividual()
    {
        return $this->belongsTo(ApplicantIndividual::class)->with('personInfo');
    }

    public function applicantLegalEntity()
    {
        return $this->belongsTo(ApplicantLegalEntity::class);
    }

}
