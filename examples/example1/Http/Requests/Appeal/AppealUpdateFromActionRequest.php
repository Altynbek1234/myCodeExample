<?php

namespace App\Http\Requests\Appeal;

use App\Models\Appeal;
use App\Models\StageAction;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;

class AppealUpdateFromActionRequest extends FormRequest
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
//        if (!in_array($action->id, $userActions) || Route::currentRouteName() !== $action->route) {
//            return false;
//        }

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
        $appealId = $this->route('id');
        $appeal = Appeal::find($appealId);

        return $appeal->stage;
    }

    public function getAction()
    {
        $action = StageAction::find($this->route('actionId'));

        return $action;
    }

}

