<?php
namespace App\Http\Requests\MeasuresToViolator;

use Illuminate\Foundation\Http\FormRequest;

class MeasuresToViolatorStoreRequest extends FormRequest
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
            'case_id' => 'required|exists:cases,id',
            'reference_employee_action_id' => 'required|exists:reference_employee_actions,id',
            'document_id' => 'exists:documents,id',
            'note' => 'nullable|string'
        ];
    }
}
