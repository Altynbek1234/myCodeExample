<?php

namespace App\Models;

use App\Interfaces\ModelInterfaces\DocumentRelationsInterface;
use App\Services\NextCloudService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ApplicantLegalEntity extends Model implements DocumentRelationsInterface
{
    use HasFactory;

    protected $fillable = [
        'olf_id',
        'name',
        'name_kg',
        'last_name_manager',
        'name_manager',
        'patronymic_manager',
        'position_manager',
        'inn',
        'okpo',
        'date_registration',
        'soate_id',
        'registration_address',
        'postal_address',
        'note',
        'status_id',
    ];

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function getDirectory(): string
    {
        return 'legal_entities/' . Str::slug($this->name) . '_' . $this->id . '/';
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
        return 'legal_entity';
    }
    public function contactPersons() {
        return $this->hasMany(ContactPerson::class,'applicant_le_id');
    }
    public function verbalAppeals()
    {
        return $this->belongsToMany(VerbalAppeal::class);
    }

    public function getNumber() {
        $selfContact = $this->contactPersons()->where('applicant_le_id',$this->id)->first();
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
}
