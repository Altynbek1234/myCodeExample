<?php

namespace App\Http\Requests\Appeal;

use Illuminate\Foundation\Http\FormRequest;

class AppealStoreRequest extends FormRequest
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
            'type_of_appeal_id' => 'required|exists:type_of_appeals,id',
            'type_appeal_count_id' => 'required|exists:type_appeal_counts,id',
            'appeal_language_id' => 'required|exists:appeal_languages,id',
            'organization_position_id' => 'required|exists:organization_position,id',
            'receipt_channel_id' => 'required|exists:receipt_channels,id',
            'representative_io_id' => 'required|exists:representatives_io,id',
            'description' => 'required|string',
            'count_of_pages' => 'nullable|integer',
            'date_of_appeal' => 'required|date',
            'number' => 'nullable|integer',
            'ish_number' => 'nullable|string',
            'ish_date' => 'nullable|date',
            'comment' => 'nullable|string',
            'applicants' => 'required|array',
            'applicants.*.id' => 'required',
            'applicants.*.type' => 'required',
            'applicants.*.appeal_interests_of' => 'required',
            'applicants.*.personList' => 'required',
            'character_of_questions' => 'nullable|array',
            'character_of_questions.*.character_of_question_id' => 'nullable|exists:character_of_questions,id',
            'character_of_questions.*.is_main' => 'required|boolean',
            'character_of_questions.*.court' => 'nullable|array',
            'character_of_questions.*.court.id' => 'nullable|exists:courts,id',
            'character_of_questions.*.court.government_agency_id' => 'nullable|exists:government_agencies,id',
            'character_of_questions.*.court.applicant_stat_monitoring_id' => 'nullable|exists:applicant_stat_monitorings,id',
            'character_of_questions.*.court.date' => 'nullable|date',
            'character_of_questions.*.court.zal_number' => 'nullable|string',
            'character_of_questions.*.court.respondent' => 'nullable|string',
            'character_of_questions.*.court.plaintiff' => 'nullable|string',
            'character_of_questions.*.court.stage' => 'nullable|string',
            'character_of_questions.*.court.defendent_id' => 'nullable|exists:defendents,id',
            'character_of_questions.*.court.reason' => 'nullable|string',
            'character_of_questions.*.court.type_proceeding_id' => 'nullable|integer',
            'category_appeal_ids' => 'nullable|array',
            'category_appeal_ids.*' => 'nullable|exists:category_appeals,id',
            'organizations' => 'required|array',
            'organizations.*.id' => 'required',
            'organizations.*.defendentList' => 'nullable',
        ];
    }
}

