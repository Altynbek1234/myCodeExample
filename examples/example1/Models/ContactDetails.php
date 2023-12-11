<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactDetails extends Model
{
    use HasFactory;

    protected $fillable = ['applicant_le_id', 'applicant_indiv_id',
        'first_name','last_name','patronymic','position',
        'mobile', 'phone','email','whatsapp','note'];

    public function getContactPerson()
    {
        return $this->belongsTo(ContactPerson::class);
    }
    public function addContactData($contactData, $personContactId)
    {
        $existsContacts = [];
        $isNew = [];
        $contactPersons = ContactPerson::findOrfail($personContactId);
        foreach ($contactData as $data) {
            if ($data['id'] && $data['id'] != null) {
                  $contactDataSingle = ContactDetails::findOrfail($data['id']);
                $contactDataSingle->contact_person_id = $personContactId;
                $contactDataSingle->type_id = $data['type_id'];
                $contactDataSingle->value = $data['value'];
                $contactDataSingle->preferred = $data['preferred'];
                $contactDataSingle->note = $data['note'];
                $contactDataSingle->update();
                $existsContacts[] = $contactDataSingle->id;
            } else {
                $contactDataSingle = new ContactDetails();
                $contactDataSingle->contact_person_id = $personContactId;
                $contactDataSingle->type_id = $data['type_id'];
                $contactDataSingle->value = $data['value'];
                $contactDataSingle->preferred = $data['preferred'];
                $contactDataSingle->note = $data['note'];
                $contactDataSingle->save();
                $isNew[] = $contactDataSingle->id;
            }
        }
        $allDataDetails = $contactPersons->getDatadetails;
        foreach ($allDataDetails as $data) {
            if(!in_array($data->id, $existsContacts) && !in_array($data->id, $isNew) ){
                $data->delete();
            }
        }
    }
}
