<?php

namespace App\Http\Requests\AppealAnswer;

use Illuminate\Foundation\Http\FormRequest;

class AppealAnswerStoreRequest extends FormRequest
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
            'appeal_id' => 'nullable|integer|exists:appeals,id',
            'applicant' => 'nullable|array',
            'issued_date' => 'required|date',
            'issued_number' => 'required|string|max:255',
            'summary' => 'nullable|string|max:255',
            'sent_date' => 'required|date',
            'outgoing_sending_channel_id' => 'required|exists:outgoing_sending_channels,id',
            'person' => 'nullable|array',
            'organization' => 'nullable|array',
            'doc_type_id' => 'required|exists:outgoingls,id',
            'executor_id' => 'required|exists:organization_employees,id',
            'whom_id' => 'nullable|integer',
            'institution_id' => 'nullable|exists:type_specialized_institutions,id',
            'another_addressee' => 'nullable|string|max:255',
        ];

    }
}

