<?php
namespace App\Http\Requests\Case;

use Illuminate\Foundation\Http\FormRequest;

class CaseStoreRequest extends FormRequest
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
            'appeal_id' => 'nullable|exists:appeals,id',
            'kind_of_case_id' => 'nullable|exists:kind_of_cases,id',
            'persons' => 'nullable',
            'start_date' => 'nullable|date',
            'summary' => 'nullable',
            'status_stated_fact_id' => 'nullable|exists:status_stated_facts,id',
            'outcome_result' => 'nullable',
            'types_of_solution_id' => 'nullable|exists:types_of_solutions,id',
            'include_in_report' => 'boolean',
            'report_text' => 'nullable',
            'organizations' => 'nullable|array',
            'organizations.*.id' => 'nullable',
            'organizations.*.defendentList' => 'nullable',
            'representative_io_id' => 'nullable|exists:representatives_io,id',
        ];
    }
}

