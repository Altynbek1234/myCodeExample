<?php

namespace App\Http\Requests\Document;

use Illuminate\Foundation\Http\FormRequest;

class DocumentStoreFromArrayRequest extends FormRequest
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
            'documents' => 'required|array',
            'documents.*.id' => 'nullable',
            'documents.*.name' => 'nullable|string',
            'documents.*.number' => 'nullable|string|max:255|min:3',
            'documents.*.issued_by' => 'nullable|string|max:255|min:3',
            'documents.*.date_of_issued' => 'nullable|date',
            'documents.*.expiry_date' => 'nullable|date',
            'documents.*.file' => 'nullable|max:51200',
            'documents.*.type_of_document_id' => 'required|exists:type_of_documents,id',
            'documents.*.appeal_id' => 'nullable|exists:appeals,id',
            'documents.*.appeal_answer_id' => 'nullable|exists:appeal_answer,id',
            'documents.*.case_id' => 'nullable|exists:cases,id',
            'documents.*.audit_registry_id' => 'nullable|exists:audit_registries,id',
            'documents.*.applicant_individual_id' => 'nullable|exists:applicant_individuals,id',
            'documents.*.applicant_legal_entity_id' => 'nullable|exists:applicant_legal_entities,id',
            'documents.*.comment' => 'nullable|string|min:3',
        ];
    }
}
