<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class VerbalAppeal extends Model
{
    use HasFactory;

    /**
     * The fields abble to change.
     *
     * @var arr
     */
    protected $fillable = [
        'type_of_appeal_id',
        'type_appeal_count_id',
        'appeal_language_id',
        'description',
        'count_of_pages',
        'date_of_appeal',
        'number',
        'ish_number',
        'ish_date',
        'comment',
        'user_id',
        'consultation_has',
        'written_appeal_has',
        'appeal_id',
        'employee_id',
        'applicants',
        'stage_id',
    ];

    /**
     * Get the type of appeal language that owns the appeal.
     */
    public function typeOfAppealLanguage()
    {
        return $this->belongsTo(AppealLanguage::class, 'appeal_language_id');
    }

    /**
     * Get the applicant legal entity that owns the appeal.
     */
    public function applicantLegalEntities()
    {
        return $this->belongsToMany(ApplicantLegalEntity::class);
    }

    public function applicantIndividuals()
    {
        return $this->belongsToMany(ApplicantIndividual::class);
    }

    /**
     * Get the person interests that owns the appeal.
     */
    public function personInterests()
    {

        return $this->belongsToMany(ApplicantIndividual::class, 'verbal_appeal_person_interest', 'verbal_appeal_id', 'person_interest_id')->withPivot('profile_id');;
    }

    public function personInterestsLegal()
    {
        return $this->belongsToMany(ApplicantLegalEntity::class, 'verbal_appeal_legal_interest', 'verbal_appeal_id', 'legal_interest_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id');
    }

    public function appeal()
    {
        return $this->belongsTo(Appeal::class, 'appeal_id');
    }

    /**
     * Get the stage history that owns the appeal.
     */
    public function stageHistories()
    {
        return $this->hasMany(StageHistory::class);
    }

    public function characterOfQuestions()
    {
        return $this->belongsToMany(CharacterOfQuestion::class, 'verbal_appeal_character_of_question', 'verbal_appeal_id', 'character_of_question_id');
    }
    public function stage()
    {
        return $this->belongsTo(Stage::class);
    }

    public function employee()
    {
        return $this->belongsTo(OrganizationEmployee::class, 'employee_id');
    }

    public function getAvailableActions()
    {
        $stage = $this->stage;
        if (!$stage) {
            return [];
        }
        $stagesAction = json_decode($stage->stagesAction, true)??[];
        $user = Auth::user();
        $stagePermissions = $user->getAvailableStagePermissions($stage->id)??[];
        $actionIds = array_keys($stagesAction);
        $stageActions = StageAction::whereIn('id', $actionIds)->whereNotIn('id', [1,2])->get();
        $availableActions = [];
        $routeParams = [
            'id' => $this->id
        ];
        if (in_array(5,$stagePermissions) && in_array(6,$stagePermissions)) {
            unset($stagePermissions[array_search(6,$stagePermissions)]);
        }
        foreach ($stageActions as $stageAction) {
            if (!in_array($stageAction->id,$stagePermissions) || !Route::has($stageAction->route)) {
                continue;
            }
            $routeParams['actionId'] = $stageAction->id;
            $action = [];
            $action['id'] = $stageAction->id;
            $action['name'] = $stageAction->name;
            $action['route'] = route($stageAction->route, $routeParams, false);
            $action['fields'] = json_decode($stageAction->fields, true)??[];
            $action['page'] = $stageAction->page;

            if(array_key_exists('appeals-number', $action['fields'])){
                $action['fields']['appeals-number']['value'] = 'У' . '-' .  $this->id;
            }

            $availableActions[$stageAction->id] = $action;
        }

        return $availableActions;
    }

    public function getLastStageHistory()
    {
        return $this->stageHistories()->orderBy('id', 'desc')->first();
    }

    public function appealCharacterOfQuestions()
    {
        return $this->hasMany(VerbalAppealCharacterOfQuestion::class, 'verbal_appeal_id', 'id');
    }

}
