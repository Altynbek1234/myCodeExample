<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactPersonRequest extends FormRequest
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
            'id' => 'nullable|integer',
            'contacts' => 'required|array',
            'applicant_le_id' => 'nullable|exists:applicant_legal_entities,id',
            'applicant_indiv_id' => 'nullable|exists:applicant_individuals,id',
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'patronymic' => 'nullable|string|max:255',
            'position_dok' => 'nullable|string|max:255',
            'note' => 'nullable|string|max:255',
        ];

    }
}
