<?php

namespace App\Http\Requests\ApplicantLegalEntity;

use Illuminate\Foundation\Http\FormRequest;

class ApplicantLERequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'name_kg' => 'nullable|string|max:255',
            'last_name_manager' => 'nullable|string|max:255',
            'name_manager' => 'nullable|string|max:255',
            'patronymic_manager' => 'nullable|string|max:255',
            'position_manager' => 'nullable|string|max:255',
            'inn' => 'nullable|string|max:255',
            'date_registration' => 'nullable|date',
            'note' => 'nullable|string|max:255',
            'status_id' => 'required|exists:applicant_statuses,id',
            'olf_id' => 'nullable|required|exists:organizational_legal_forms,id',
            'okpo' => 'nullable|string|max:255',
            'soate_registration_address' => 'nullable|string|max:255',
            'soate_postal_address' => 'nullable|string|max:255',
            'registration_address' => 'nullable|string|max:255',
            'postal_address' => 'nullable|string|max:255',
        ];



    }
}
