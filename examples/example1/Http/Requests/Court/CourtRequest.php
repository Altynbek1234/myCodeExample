<?php

namespace App\Http\Requests\Court;

use Illuminate\Foundation\Http\FormRequest;

class CourtRequest extends FormRequest
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
            'government_agency_id' => 'required|exists:government_agencies,id',
            'date' => 'required|date',
            'zal_number' => 'nullable|string',
            'respondent' => 'nullable|string',
            'plaintiff' => 'nullable|string',
            'stage' => 'nullable|string',
            'defendent_id' => 'nullable|exists:defendents,id',
        ];
    }
}
