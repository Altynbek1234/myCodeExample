<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactDetailsReqauest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'applicant_le_id' => 'exists:applicant_legal_entities,id',
            'applicant_indiv_id' => 'exists:applicant_individuals,id',
            'preferred_method' => 'exists:type_contact_data,id',
            'first_name' => 'string|max:255',
            'last_name' => 'string|max:255',
            'patronymic' => 'string|max:255',
            'position' => 'string|max:255',
            'mobile' => 'required|string|max:255',
            'phone' => 'string|max:255',
            'email' => 'string|max:255',
            'whatsapp' => 'string|max:255',
            'note' => 'string|max:255',
        ];

    }
}
