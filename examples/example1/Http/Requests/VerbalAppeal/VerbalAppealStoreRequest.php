<?php

namespace App\Http\Requests\VerbalAppeal;

use Illuminate\Foundation\Http\FormRequest;

class VerbalAppealStoreRequest extends FormRequest
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
            'appeal_language_id' => 'required|exists:appeal_languages,id',
            'employee_id' => 'nullable|exists:organization_employees,id',
            'description' => 'required|string',
            'date_of_appeal' => 'required|date',
            'number' => 'nullable|integer',
            'ish_number' => 'nullable|string',
            'ish_date' => 'nullable|date',
            'consultation_has' => 'boolean',
            'written_appeal_has' => 'boolean',
            'appeal_id' => 'nullable|exists:appeals,id',
            'applicants' => 'required|array',
            'character_of_questions' => 'nullable|array',
            'character_of_questions.*.character_of_question_id' => 'nullable|exists:character_of_questions,id',
            'character_of_questions.*.is_main' => 'required|boolean',
            'character_of_questions.*.court' => 'nullable|array',
            'character_of_questions.*.court.id' => 'nullable|exists:courts,id',
            'character_of_questions.*.court.organization_complaint_id' => 'nullable|exists:organization_complaints,id',
            'character_of_questions.*.court.date' => 'nullable|date',
            'character_of_questions.*.court.zal_number' => 'nullable|string',
            'character_of_questions.*.court.respondent' => 'nullable|string',
            'character_of_questions.*.court.plaintiff' => 'nullable|string',
            'character_of_questions.*.court.stage' => 'nullable|string',
            'character_of_questions.*.court.defendent_id' => 'nullable|exists:defendents,id',
            'category_appeal_ids' => 'nullable|array',
            'category_appeal_ids.*' => 'nullable|exists:category_appeals,id',
            'organizations' => 'nullable|array',
        ];
    }
}

