<?php

namespace App\Http\Requests\Case;

use Illuminate\Foundation\Http\FormRequest;

class CaseActionsRequest extends FormRequest
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
            'appeal_id' => 'nullable|integer',
            'action_id' => 'nullable|integer',
            'date' => 'required|date',
            'description' => 'nullable|string',
            'executors' => 'nullable|array',
            'place' => 'nullable|string',
            'appeal_answer_id' => 'nullable|integer'
        ];
    }
}
