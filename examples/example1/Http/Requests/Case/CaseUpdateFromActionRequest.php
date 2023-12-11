<?php

namespace App\Http\Requests\Case;

use App\Models\Appeal;
use App\Models\AppealAnswer;
use App\Models\CaseDeal;
use App\Models\StageAction;
use App\Models\VerbalAppeal;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;

class CaseUpdateFromActionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $action = $this->getAction();
        $stage = $this->getStage();
        $userActions = $this->user()->getAvailableStagePermissions($stage->id);
        if (!in_array($action->id, $userActions) || Route::currentRouteName() !== $action->route) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $rules = [];
        $fields = json_decode($this->getAction()->fields, true) ?? [];
        foreach ($fields as $key => $field) {
            $rules[$key] = $field['validation'];
            if (isset($field['nullable']) && !$field['nullable']) {
                $rules[$key] .= '|required';
            } else {
                $rules[$key] .= '|nullable';
            }
        }

        return $rules;
    }

    public function getStage()
    {
        $caseId = $this->route('id');
        $case = CaseDeal::find($caseId);

        return $case->stage;
    }

    public function getAction()
    {
        $action = StageAction::find($this->route('actionId'));

        return $action;
    }

}

