<?php

namespace App\Models;

use App\Services\NextCloudService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class AuditRegistry extends Model
{
    use HasFactory;


    protected $fillable = [
        'types_of_inspection_id',
        'date_check',
        'start_date',
        'end_date',
        'institutions_for_monitoring_id',
        'basis_of_verification',
        'appeal_id',
        'surname_of_responsible',
        'name_of_responsible',
        'middle_name',
        'position',
        'contact_number',
        'document',
        'inspection_result_id',
        'fio_position',
        'conclusions',
        'organization_employees',
        'directory_of_violations',
        'case_deals',
        'impact',
        'impact_measures_taken',
        'defendants',
        'position_governmental_id',
        'note',
        'stage_id',
        'number'
    ];

    public function organizationEmployees()
    {
        return $this->belongsToMany(OrganizationEmployees::class, 'audit_registry_organization_employee', 'audit_registry_id', 'organization_employee_id')
            ->withTimestamps()
            ->withPivot('is_main');
    }

    public function positionGovernmental()
    {
        return $this->hasOne(PositionGovernmental::class, 'id', 'position_governmental_id');
    }

    public function detectedViolations()
    {
        return $this->belongsToMany(DetectedViolation::class, 'audit_registry_detected_violation', 'audit_registry_id', 'detected_violation_id');
    }

    public function caseDeals()
    {
        return $this->belongsToMany(CaseDeal::class, 'audit_registry_case_deal')->withTimestamps();
    }

    public function impactMeasuresTaken()
    {
        return $this->belongsToMany(ImpactMeasuresTaken::class, 'audit_registry_impact_measures_taken');
    }

    public function defendant()
    {
        return $this->belongsToMany(Defendant::class, 'defendant_audit_registry')->withTimestamps();
    }

    public function typesOfInspection()
    {
        return $this->belongsTo(TypesOfInspection::class, 'types_of_inspection_id');
    }

    public function institutionForMonitoring()
    {
        return $this->belongsTo(InstitutionsForMonitoring::class, 'institutions_for_monitoring_id');
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

        if(array_key_exists('auditRegistries-number', $formattedAction['fields'])){
            $formattedAction['fields']['auditRegistries-number']['value'] = self::getNewAuditRegistryNumber();
        }

        return $formattedAction;
    }

    public static function getNewAuditRegistryNumber()
    {
        return self::whereNotNull('number')->count() + 1 . '/' . date('Y');
    }

    public function getDirectory(): string
    {
        return 'audit-registries/' . Str::slug('audit_registry') . '_' . $this->id . '/';
    }

    public function documents()
    {
        return $this->hasMany(File::class, 'audit_registry_id', 'id');
    }

    public function shareDocument($filename)
    {
        $service = new NextCloudService();
        $link = $service->shareFile($filename, 'beka');

        return $link;
    }

}
