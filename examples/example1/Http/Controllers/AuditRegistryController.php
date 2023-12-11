<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuditRegistry\AuditRegistryRequest;
use App\Http\Requests\AuditRegistry\AuditRegistryUpdateFromActionRequest;
use App\Http\Resources\AuditRegistry\AuditRegistryResource;
use App\Models\AuditRegistry;
use App\Models\Stage;
use App\Models\StageAction;
use App\Models\StageHistory;
use Illuminate\Http\Request;

class AuditRegistryController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/audit",
     *     tags={"реестр проверок"},
     *     summary="Получение списка проверок",
     *     description="Получение списка проверок",
     *     operationId="audit.index",
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/AuditResource")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *     )
     * )
     */
    public function index(Request $request)
    {
        $orderBy = $request->input('order_by', 'date_check');
        $orderDir = $request->input('order_dir', 'desc');
        $typesOfInspection = $request->input('types_of_inspection_id');
        $instutution = $request->input('institutions_for_monitoring_id');
        $employee_id = $request->input('employee_id');
        $query = AuditRegistry::query()
            ->with('typesOfInspection')
            ->with('institutionForMonitoring')
            ->with('organizationEmployees')
            ->orderBy($orderBy, $orderDir);
        if ($typesOfInspection) {
            $query->whereHas('typesOfInspection', function ($query) use ($typesOfInspection) {
                $query->where('types_of_inspection_id', $typesOfInspection);
            });
        }

        if ($instutution) {
            $query->whereHas('institutionForMonitoring', function ($query) use ($instutution) {
                $query->where('institutions_for_monitoring_id', $instutution);
            });
        }

        if ($employee_id) {
            $query->whereHas('organizationEmployees', function ($query) use ($employee_id) {
                $query->where('audit_registry_organization_employee.organization_employee_id', $employee_id);
            });
        }

        $auditRegistries = $query->paginate(10);

        return AuditRegistryResource::collection($auditRegistries);
    }

    /**
     * @OA\Get(
     *     path="/api/audit/{id}/show",
     *     tags={"Проверки"},
     *     summary="Получение проверки",
     *     description="Получение проверки",
     *     operationId="audit.show",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID проверки",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(ref="#/components/schemas/AuditRegistryResource")
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not found",
     *      ),
     *      @OA\Response(
     *          response=419,
     *          description="CSRF token mismatch"
     *      )
     * )
     */
    public function show(AuditRegistry $id)
    {
        return new AuditRegistryResource($id);
    }

    /**
     * @OA\Post(
     *     path="/api/audit",
     *     tags={"реестр проверок"},
     *     summary="Создание проверки",
     *     description="Создание проверки",
     *     operationId="audit.store",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/AuditRegistryRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(ref="#/components/schemas/AuditRegistryResource")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *     ),
     *     @OA\Response(
     *         response=419,
     *         description="CSRF token mismatch"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found"
     *     )
     * )
     */
    public function store(AuditRegistryRequest $request)
    {
        $validatedData = $request->validated();

        $auditRegistry = AuditRegistry::create([
            'types_of_inspection_id' => $validatedData['types_of_inspection_id'],
            'date_check' => $validatedData['date_check'],
            'start_date' => $validatedData['start_date'],
            'end_date' => $validatedData['end_date'],
            'institutions_for_monitoring_id' => $validatedData['institutions_for_monitoring_id'],
            'basis_of_verification' => $validatedData['basis_of_verification'],
            'appeal_id' => $validatedData['appeal_id'],
            'surname_of_responsible' => $validatedData['surname_of_responsible'],
            'name_of_responsible' => $validatedData['name_of_responsible'],
            'middle_name' => $validatedData['middle_name'],
            'position' => $validatedData['position'],
            'contact_number' => $validatedData['contact_number'],
            'document' => $validatedData['document'],
            'inspection_result_id' => $validatedData['inspection_result_id'] ?? null,
            'conclusions' => $validatedData['conclusions'],
            'note' => $validatedData['note'],
            'position_governmental_id' => $validatedData['position_governmental_id'],
            'stage_id' => 29
        ]);

        $stageHistory = StageHistory::create([
            'audit_registry_id' => $auditRegistry->id,
            'stage_id' => StageHistory::START_STAGE_AUDIT_REGISTRY_ID,
            'user_id' => auth()->user()->id,
        ]);

        $organizationEmployees = $validatedData['organization_employees'];

        $syncData = [];
        foreach ($organizationEmployees as $employee) {
            $syncData[$employee['id']] = ['is_main' => $employee['is_main']];
        }

        $auditRegistry->organizationEmployees()->sync($syncData);
        $auditRegistry->detectedViolations()->sync($validatedData['detected_violations'] ?? null);
        $auditRegistry->caseDeals()->sync($validatedData['case_deals'] ?? null);
        $auditRegistry->impactMeasuresTaken()->sync($validatedData['impact_measures_taken'] ?? null);
        $auditRegistry->defendant()->sync($validatedData['defendants'] ?? null);

        $auditRegistry->stageHistories()->save($stageHistory);
        return new AuditRegistryResource($auditRegistry);
    }

    /**
     * @OA\Put(
     *     path="/api/audit/{id}",
     *     tags={"реестр проверок"},
     *     summary="Обновление проверки",
     *     description="Обновление проверки",
     *     operationId="audit.update",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID проверки",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/AuditRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(ref="#/components/schemas/AuditResource")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *     ),
     *     @OA\Response(
     *         response=419,
     *         description="CSRF token mismatch"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found"
     *     )
     * )
     */
    public function update(AuditRegistryRequest $request, $id)
    {
        $validatedData = $request->validated();

        $auditRegistry = AuditRegistry::findOrFail($id);

        $auditRegistry->update([
            'types_of_inspection_id' => $validatedData['types_of_inspection_id'],
            'date_check' => $validatedData['date_check'],
            'start_date' => $validatedData['start_date'],
            'end_date' => $validatedData['end_date'],
            'institutions_for_monitoring_id' => $validatedData['institutions_for_monitoring_id'],
            'basis_of_verification' => $validatedData['basis_of_verification'],
            'appeal_id' => $validatedData['appeal_id'],
            'surname_of_responsible' => $validatedData['surname_of_responsible'],
            'name_of_responsible' => $validatedData['name_of_responsible'],
            'middle_name' => $validatedData['middle_name'],
            'position' => $validatedData['position'],
            'contact_number' => $validatedData['contact_number'],
            'document' => $validatedData['document'],
            'inspection_result_id' => $validatedData['inspection_result_id'] ?? null,
            'conclusions' => $validatedData['conclusions'],
            'note' => $validatedData['note'],
            'position_governmental_id' => $validatedData['position_governmental_id'],
        ]);

        $organizationEmployees = $validatedData['organization_employees'];

        $syncData = [];
        foreach ($organizationEmployees as $employee) {
            $syncData[$employee['id']] = ['is_main' => $employee['is_main']];
        }

        $auditRegistry->organizationEmployees()->sync($syncData);
        $auditRegistry->detectedViolations()->sync($validatedData['detected_violations'] ?? null);
        $auditRegistry->caseDeals()->sync($validatedData['case_deals'] ?? null);
        $auditRegistry->impactMeasuresTaken()->sync($validatedData['impact_measures_taken'] ?? null);
        $auditRegistry->defendant()->sync($validatedData['defendants'] ?? null);

        return new AuditRegistryResource($auditRegistry);
    }

    public function updateFromAction(AuditRegistryUpdateFromActionRequest $request, $id, $actionId)
    {
        $auditRegistry = AuditRegistry::findOrfail($id);
        $lastStageHistory = $auditRegistry->getLastStageHistory();
        $action = StageAction::find($actionId);
        $fields = $request->validated();
        $stage = Stage::find($action->next_stage_id);
        $stageHistories = [
            'audit_registry_id' => $auditRegistry->id,
            'action_id' => $action->id,
            'stage_id' => $action->next_stage_id,
            'prev_stage_id' => $auditRegistry->stage_id,
            'prev_stage_history_id' => !empty($lastStageHistory) ? $lastStageHistory->id : 29,
            'user_id' => auth()->user()->id,
        ];
        $auditRegistries = [
            'stage_id' => $action->next_stage_id,
        ];

        $auditRegistriesHistory = [];

        foreach ($fields as $key => $value) {
            [$tableName, $fieldName] = explode('-', $key);
            ${$tableName}[$fieldName] = $value;
            $auditRegistriesHistory['auditRegistries'][$fieldName] = $auditRegistry->{$fieldName};
        }
        $stageHistories['fields_history'] = json_encode($auditRegistriesHistory);
        StageHistory::create($stageHistories);
        $auditRegistry->update($auditRegistries);

        return new AuditRegistryResource($auditRegistry);
    }


    public function dawngradeFromAction($id, $actionId)
    {
        $auditRegistry = AuditRegistry::findOrfail($id);
        $action = StageAction::find($actionId);
        $lastStageHistory = $auditRegistry->getLastStageHistory();
        $stageHistoryForRevert = StageHistory::find($lastStageHistory->prev_stage_history_id);
        $newStageHistory = $stageHistoryForRevert->replicate();
        $newStageHistory->action_id = $action->id;
        $newStageHistory->user_id = auth()->user()->id;
        $newStageHistory->save();

        $auditRegistry->stage_id = $stageHistoryForRevert->stage_id;
        $auditRegistry->save();
        $fieldsHistory = json_decode($lastStageHistory->fields_history, true);
        if (!empty($fieldsHistory['auditRegistries'])) {
            $auditRegistries = $fieldsHistory['auditRegistries'];
            $auditRegistry->update($auditRegistries);
        }

        return new AuditRegistryResource($auditRegistry);
    }

    /**
     * @OA\Delete(
     *     path="/api/audit/{id}",
     *     tags={"реестр проверок"},
     *     summary="Удаление проверки",
     *     description="Удаление проверки",
     *     operationId="audit.destroy",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID проверки",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="No Content"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found"
     *     )
     * )
     */
    public function destroy($id)
    {
        $auditRegistry = AuditRegistry::findOrFail($id);
        $auditRegistry->delete();

        return response()->noContent();
    }
}
