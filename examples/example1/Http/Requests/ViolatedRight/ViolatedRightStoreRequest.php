<?php
namespace App\Http\Requests\ViolatedRight;

use Illuminate\Foundation\Http\FormRequest;

class ViolatedRightStoreRequest extends FormRequest
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
            'violations_classifier_id' => 'required|exists:violations_classifier,id',
            'government_agency_id' => 'required|exists:government_agencies,id',
            'defendants' => 'nullable|array',
            'note' => 'nullable|string'
        ];
    }
}
