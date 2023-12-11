<?php

namespace App\Http\Requests\References\Defendent;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'name' => 'required|string|max:255|min:2',
            'last_name' => 'required|string|max:255|min:2',
            'patronymic' => 'nullable|string|max:255|min:2',
            'born_date' => 'nullable|date',
            'inn' => 'nullable|string|max:255|min:2',
            'gender_id' => 'required|exists:genders,id',
            'government_agency_id' => 'nullable|exists:government_agencies,id',
            'position_governmental_id' => 'nullable|exists:position_governmentals,id'
        ];
    }
}
