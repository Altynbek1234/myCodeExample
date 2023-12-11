<?php

namespace App\Http\Requests\AuditRegistry;

use Illuminate\Foundation\Http\FormRequest;

class AuditRegistryRequest extends FormRequest
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
            'types_of_inspection_id' => 'required',
            'date_check' => 'required|date',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'institutions_for_monitoring_id' => 'nullable',
            'basis_of_verification' => 'required|string',
            'appeal_id' => 'nullable',
            'surname_of_responsible' => 'nullable|string',
            'name_of_responsible' => 'nullable|string',
            'middle_name' => 'nullable|string',
            'position' => 'nullable|string',
            'contact_number' => 'nullable|string',
            'document' => 'nullable|string',
            'inspection_result_id' => 'nullable',
            'conclusions' => 'nullable|string',
            'organization_employees' => 'required|array',
//            'organization_employees.*' => 'exists:organization_employees,id',
            'detected_violations' => 'nullable|array',
            'detected_violations.*' => 'exists:detected_violations,id',
            'case_deals' => 'nullable|array',
            'case_deals.*' => 'exists:cases,id',
            'impact_measures_taken' => 'nullable|array',
            'impact_measures_taken.*' => 'exists:impact_measures_takens,id',
            'defendants' => 'nullable|array',
            'defendants.*' => 'exists:defendents,id',
            'position_governmental_id' => 'exists:position_governmentals,id',
            'note' => 'nullable|string',

        ];
    }
}
