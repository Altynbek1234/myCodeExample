<?php

namespace App\Models;

use App\Services\NextCloudService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class Appeal extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'appeals';

    /**
     * The fields abble to change.
     *
     * @var arr
     */
    protected $fillable = [
        'type_of_appeal_id',
        'type_appeal_count_id',
        'appeal_language_id',
        'receipt_channel_id',
        'frequenciesy_of_appeal_id',
        'organization_position_id',
        'representative_io_id',
        'description',
        'count_of_pages',
        'date_of_appeal',
        'number',
        'ish_number',
        'ish_date',
        'comment',
        'stage_id',
        'applicants',
        'in_control',
        'leader_id',
        'organization_structure_id',
        'read',
        'favorite'
    ];

    protected $casts = [
        'read' => 'array',
        'favorite' => 'array'
    ];

    public function case()
    {
        return $this->hasOne(CaseDeal::class);
    }

    /**
     * Get the type of appeal that owns the appeal.
     */
    public function typeOfAppeal()
    {
        return $this->belongsTo(TypeOfAppeal::class);
    }

    /**
     * Get the type of appeal by count that owns the appeal.
     */
    public function typeOfAppealByCount()
    {
        return $this->belongsTo(TypeOfAppealByCount::class, 'type_appeal_count_id');
    }

    /**
     * Get the type of appeal language that owns the appeal.
     */
    public function typeOfAppealLanguage()
    {
        return $this->belongsTo(AppealLanguage::class, 'appeal_language_id');
    }

    /**
     * Get the frequency of appeal that owns the appeal.
     */
    public function frequencyOfAppeal()
    {
        return $this->belongsTo(FrequencyOfAppeal::class, 'frequenciesy_of_appeal_id');
    }

/**
     * Get the organization position that owns the appeal.
     */
    public function organizationPosition()
    {
        return $this->belongsTo(OrganizationPosition::class);
    }

    /**
     * Get the receipt channel that owns the appeal.
     */
    public function receiptChannel()
    {
        return $this->belongsTo(ReceiptChannel::class);
    }

    /**
     * Get the representative io that owns the appeal.
     */
    public function representativeIo()
    {
        return $this->belongsTo(RepresentativeIO::class);
    }

    /**
     * Get the applicant individual that owns the appeal.
     */
    public function applicantIndividuals()
    {
        return $this->belongsToMany(ApplicantIndividual::class);
    }

    /**
     * Get the applicant legal entity that owns the appeal.
     */
    public function applicantLegalEntities()
    {
        return $this->belongsToMany(ApplicantLegalEntity::class);
    }

    /**
     * Get the person interests that owns the appeal.
     */
    public function personInterests()
    {
        return $this->belongsToMany(ApplicantIndividual::class, 'appeal_person_interest', 'appeal_id', 'person_interest_id')->withPivot('profile_id');
    }

    /**
     * Get the person interests that owns the appeal.
     */
    public function personInterestsLegal()
    {
        return $this->belongsToMany(ApplicantLegalEntity::class, 'appeal_legal_interest', 'appeal_id', 'legal_interest_id');
    }

    /**
     * Get the character of question that owns the appeal.
     */
    public function characterOfQuestions()
    {
        return $this->belongsToMany(CharacterOfQuestion::class, 'appeal_character_of_question', 'appeal_id', 'character_of_question_id');
    }

    /**
     * Get the category of appeal that owns the appeal.
     */
    public function categoriesOfAppeal()
    {
        return $this->belongsToMany(CategoryAppeal::class);
    }

    /**
     * Get the organizations complaints that owns the appeal.
     */
    public function organizations()
    {
        return $this->belongsToMany(GovernmentAgency::class)->withPivot('defendent_ids');
    }

    /**
     * Get the stage history that owns the appeal.
     */
    public function stageHistories()
    {
        return $this->hasMany(StageHistory::class);
    }

    public function appealDoers()
    {
        return $this->hasMany(AppealDoer::class);
    }

    public function appealDates()
    {
        return $this->hasMany(AppealDate::class);
    }

    public function employeeActions()
    {
        return $this->hasMany(EmployeeAction::class, 'appeal_id');
    }

    public function violatedRights()
    {
        return $this->hasMany(ViolatedRight::class, 'appeal_id');
    }

    /**
     * Get the appeal character of questions that owns the appeal.
     */
    public function appealCharacterOfQuestions()
    {
        return $this->hasMany(AppealCharacterOfQuestion::class, 'appeal_id', 'id');
    }

    public function getLastStageHistory()
    {
        return $this->stageHistories()->orderBy('id', 'desc')->first();
    }

    public function appealActions()
    {
        return $this->hasMany(CaseAction::class, 'appeal_id');
    }

    public function getResultStage()
    {
        return $this->stageHistories()
            ->whereHas('stage', function ($query) {
                $query->where('end_stage', true);
            })
            ->orderBy('id', 'desc')
            ->with('stage')
            ->first();
    }

    public function stage()
    {
        return $this->belongsTo(Stage::class);
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

        if (in_array(5,$stagePermissions) && in_array(6,$stagePermissions)) {
            unset($stagePermissions[array_search(6,$stagePermissions)]);
        }
        if (in_array(3,$stagePermissions)) {
            unset($stagePermissions[array_search(3,$stagePermissions)]);
        }
        foreach ($stageActions as $stageAction) {
            if (!in_array($stageAction->id,$stagePermissions) || !Route::has($stageAction->route)) {
                continue;
            }
            $availableActions[$stageAction->id] = $this->getFormattedAction($stageAction);
        }

        return $availableActions;
    }

    public function getFormattedFieldsGroup($fieldsGroup)
    {
        $fieldsGroup = json_decode($fieldsGroup, true);
        if (empty($fieldsGroup)) {
            return [];
        }

        $formattedFieldsGroup = [
            'actions' => [],
            'name_ru' => $fieldsGroup['name_ru']??'',
            'name_kg' => $fieldsGroup['name_kg']??'',
        ];

        foreach ($fieldsGroup['actions'] as $actionId) {
            $action = StageAction::find($actionId);
            $formattedFieldsGroup['actions'][$action['name']] = $this->getFormattedAction($action);
        }

        return $formattedFieldsGroup;
    }

    public function getFormattedAction($action)
    {
        $routeParams = [
            'appeal_id' => $this->id,
            'id' => $this->id,
            'actionId' => $action->id
        ];
        $lastStageHistory = $this->getLastStageHistory();

        $formattedAction = [];
        $formattedAction['id'] = $action->id;
        $formattedAction['name'] = $action->name;
        $formattedAction['route'] = route($action->route, $routeParams, false);
        $formattedAction['fields'] = json_decode($action->fields, true)??[];
        $formattedAction['fieldsGroup'] = $this->getFormattedFieldsGroup($action->fields_group);
        $formattedAction['page'] = $action->page;

        if(array_key_exists('appeals-number', $formattedAction['fields'])){
            $formattedAction['fields']['appeals-number']['value'] = $this->getNewNumber();
        }
        if (array_key_exists('stageHistories-resolution', $formattedAction['fields']) && $lastStageHistory->resolution) {
            $formattedAction['fields']['stageHistories-resolution']['value'] = $lastStageHistory->resolution??'';
        }
        if (array_key_exists('appeals-organization_structure_id', $formattedAction['fields']) && $this->organization_structure_id) {
            $formattedAction['fields']['appeals-organization_structure_id']['value'] = $this->organization_structure_id ?? '';
        }
        if(array_key_exists('number', $formattedAction['fields'])){
            $formattedAction['fields']['number']['value'] = CaseDeal::getNewCaseNumber();
        }

        return $formattedAction;
    }

    public function documents()
    {
        return $this->hasMany(File::class, 'appeal_id', 'id');
    }

    public function getDirectory(): string
    {
        return 'appeals/' . Str::slug('appeal') . '_' . $this->id . '/';
    }

    public function shareDocument($filename)
    {
        $service = new NextCloudService();
        $link = $service->shareFile($filename, 'beka');

        return $link;
    }

    public function getNewNumber()
    {
        $letter = '';
        foreach ($this->applicantIndividuals as $applicant){
            if ($applicant->personInfo->name) {
                $letter = mb_substr($applicant->personInfo->name, 0, 1, 'UTF-8');
                break;
            }
        }

        if ($letter == '') {
            foreach ($this->applicantLegalEntities() as $applicant){
                if ($applicant && $applicant->name) {
                    $letter = mb_substr($applicant->name, 0, 1, 'UTF-8');
                    break;
                }
            }
        }
        $number = ($letter && trim($letter) !== '' ? $letter : 'П') . '-' . self::getNewAppealNumber();

        return $number;
    }

    public static function getNewAppealNumber()
    {
        return self::whereNotNull('number')->count() + 1;
    }
}
