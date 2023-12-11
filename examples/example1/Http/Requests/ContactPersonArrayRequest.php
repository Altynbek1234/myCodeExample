<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactPersonArrayRequest extends FormRequest
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
            'contacts' => 'required|array',
            'contacts.*.id' => 'nullable|integer',
            'contacts.*.name' => 'required|string',
            'contacts.*.last_name' => 'required|string',
            'contacts.*.patronymic' => 'nullable|string',
            'contacts.*.position_dok' => 'nullable|string',
            'contacts.*.note' => 'nullable|string',
            'contacts.*.contact_details' => 'required|array',
            'contacts.*.contact_details.*.id' => 'nullable|integer',
            'contacts.*.contact_details.*.type_id' => 'required|integer',
            'contacts.*.contact_details.*.value' => 'nullable|string',
            'contacts.*.contact_details.*.preferred' => 'nullable|boolean',
            'contacts.*.contact_details.*.note' => 'nullable|string',
        ];
    }
}
