<?php

namespace App\Models;

use App\Services\NextCloudService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Orchid\Attachment\Attachable;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class CaseDeal extends Model
{
    use HasFactory, AsSource, Filterable, Attachable;

    protected $table = 'cases';

    protected $fillable = [
        'appeal_id',
        'kind_of_case_id',
        'start_date',
        'summary',
        'status_stated_fact_id',
        'outcome_result',
        'include_in_report',
        'types_of_solution_id',
        'report_text',
        'stage_id',
        'number',
        'representative_io_id'
    ];

    public function appeal()
    {
        return $this->belongsTo(Appeal::class);
    }

    public function kindOfCase()
    {
        return $this->belongsTo(KindOfCase::class);
    }

    public function persons()
    {
        return $this
            ->belongsToMany(ApplicantIndividual::class, 'case_person', 'case_id', 'person_id')
            ->withPivot('profile_id')
            ->withTimestamps();
    }

    public function organizations()
    {
        return $this->belongsToMany(GovernmentAgency::class, 'case_government_agency', 'case_id', 'government_agency_id')->withPivot('defendent_ids')->withTimestamps();
    }

    public function statusStatedFact()
    {
        return $this->belongsTo(StatusStatedFact::class);
    }

    public function typesOfSolution()
    {
        return $this->belongsTo(TypesOfSolution::class);
    }

    public function stage()
    {
        return $this->belongsTo(Stage::class);
    }

    /**
     * Get the stage history that owns the appeal.
     */
    public function stageHistories()
    {
        return $this->hasMany(StageHistory::class);
    }

    public function getLastStageHistory()
    {
        return $this->stageHistories()->orderBy('id', 'desc')->first();
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
        if ($this->appeal && in_array(38,$stagePermissions)) {
            unset($stagePermissions[array_search(38,$stagePermissions)]);
        }
        if ($this->appeal && in_array(39,$stagePermissions)) {
            unset($stagePermissions[array_search(39,$stagePermissions)]);
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

        if(array_key_exists('case-number', $formattedAction['fields'])){
            $formattedAction['fields']['case-number']['value'] = self::getNewCaseNumber();
        }

        return $formattedAction;
    }

    public function getDirectory(): string
    {
        return 'cases/' . Str::slug('case') . '_' . $this->id . '/';
    }

    public function documents()
    {
        return $this->hasMany(File::class, 'case_id', 'id');
    }

    public function shareDocument($filename)
    {
        $service = new NextCloudService();
        $link = $service->shareFile($filename, 'beka');

        return $link;
    }

    public function caseActions()
    {
        return $this->hasMany(CaseAction::class, 'case_id');
    }

    public function employeeActions()
    {
        return $this->hasMany(EmployeeAction::class, 'case_id');
    }

    public function violatedRights()
    {
        return $this->hasMany(ViolatedRight::class, 'case_id');
    }

    public function caseDoers()
    {
        return $this->hasMany(CaseDoer::class, 'case_id');
    }

    public function caseDates()
    {
        return $this->hasMany(CaseDate::class, 'case_id');
    }

    public static function getNewCaseNumber()
    {
        return self::whereNotNull('number')->count() + 1 . '/' . date('Y');
    }

    public function representativeIo()
    {
        return $this->belongsTo(RepresentativeIO::class);
    }

}
