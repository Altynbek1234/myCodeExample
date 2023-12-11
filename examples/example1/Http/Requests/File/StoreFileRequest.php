<?php

namespace App\Http\Requests\File;

use Illuminate\Foundation\Http\FormRequest;

class StoreFileRequest extends FormRequest
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
            'file' => 'required|file',
            'comment' => 'nullable|string',
            'appeal_id' => 'required|integer|exists:appeals,id',
            'case_id' => 'required|integer|exists:cases,id',
            'appeal_answer_id' => 'required|integer|exists:appeal_answer,id',
            'type_of_document_id' => 'required|integer|exists:type_of_documents,id',
            'audit_registry_id' => 'required|integer|exists:audit_registries,id',
        ];
    }
}
