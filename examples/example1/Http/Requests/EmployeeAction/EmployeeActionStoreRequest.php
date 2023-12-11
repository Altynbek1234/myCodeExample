<?php
namespace App\Http\Requests\EmployeeAction;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeActionStoreRequest extends FormRequest
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
            'case_id' => 'nullable|exists:cases,id',
            'appeal_id' => 'nullable|exists:appeals,id',
            'government_agency_id' => 'required|exists:government_agencies,id',
            'defendants' => 'nullable|array',
            'action_to_violator_id' => 'required|exists:action_to_violators,id',
            'document_id' => 'nullable|exists:documents,id',
            'date' => 'required|string',
            'note' => 'nullable|string',
            'violated_right_id' => 'required|exists:violated_rights,id'
        ];
    }
}
