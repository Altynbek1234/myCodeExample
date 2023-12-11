<?php

namespace App\Http\Requests\Document;

use Illuminate\Foundation\Http\FormRequest;

class DocumentStoreRequest extends FormRequest
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
            'number' => 'nullable|string|max:255|min:3',
            'issued_by' => 'nullable|string|max:255|min:3',
            'name' => 'nullable|string',
            'date_of_issued' => 'nullable|date',
            'expiry_date' => 'nullable|date',
            'file' => 'required|max:512',
            'type_of_document_id' => 'required|exists:type_of_documents,id',
            'applicant_individual_id' => 'nullable|exists:applicant_individuals,id',
            'applicant_legal_entity_id' => 'nullable|exists:applicant_legal_entities,id',
            'comment' => 'nullable|string|min:3',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $data = $this->all();

            if (!isset($data['applicant_individual_id']) && !isset($data['applicant_legal_entity_id'])) {
               $validator->errors()->add('owner', 'Укажите кому принадлежит документ.');
            }
        });
    }
}
