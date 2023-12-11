<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeAction\EmployeeActionStoreRequest;
use App\Http\Resources\EmployeeAction\EmployeeActionShowResource;
use App\Models\EmployeeAction;
use App\Services\NextCloudService;
use Illuminate\Http\Request;
use App\Http\Resources\Case\CaseShowResource;
use Symfony\Component\HttpFoundation\Response;




class EmployeeActionController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/employee_action",
     *     tags={"Данные Предпринятыx действий сотрудников Акыйкатчы"},
     *     summary="Создание Предпринятыx действий сотрудников Акыйкатчы",
     *     description="Создание Предпринятыx действий сотрудников Акыйкатчы",
     *     operationId="employee_action.store",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/EmployeeActionStoreRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(ref="#/components/schemas/EmployeeActionShowResource")
     *     ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=419,
     *          description="CSRF token mismatch"
     *      )
     * )
     */

    public function store(EmployeeActionStoreRequest $request)
    {
        $data = $request->validated();
        $employeeAction = EmployeeAction::create([
            'case_id' => $data['case_id'],
            'government_agency_id' => $data['government_agency_id'],
            'note' => $data['note'],
            'action_to_violator_id' => $data['action_to_violator_id'],
            'document_id' => $data['document_id'],
            'date' => $data['date'],
            'defendants' => json_encode($data['defendants']),
            'violated_right_id' => $data['violated_right_id'],
            'appeal_id' => $data['appeal_id'] ?? null,
        ]);

        return (new EmployeeActionShowResource($employeeAction))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);

    }

    /**
     * @OA\Get(
     *      path="api/employee_action/{id}",
     *     tags={"Данные Предпринятыx действий сотрудников Акыйкатчы"},
     *     summary="Получение Предпринятыx действий сотрудников Акыйкатчы",
     *      description="Получение Предпринятыx действий сотрудников Акыйкатчы",
     *      @OA\Parameter(
     *              name="id",
     *              description="id данных",
     *              required=true,
     *              in="path",
     *              @OA\Schema(
     *                  type="integer"
     *              )
     *          ),
     *          @OA\Response(
     *              response=200,
     *              description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/EmployeeActionShowResource")
     *          ),
     *          @OA\Response(
     *              response=401,
     *              description="Unauthenticated",
     *          ),
     *          @OA\Response(
     *              response=404,
     *              description="Not Found"
     *          ),
     *          @OA\Response(
     *              response=419,
     *              description="CSRF token mismatch"
     *          ),
     *          @OA\Response(
     *              response=403,
     *              description="Forbidden"
     *          ),
     *          @OA\Response(
     *              response=500,
     *              description="Server Error"
     *          )
     *     )
     */
    public function show($id)
    {
        $employeeAction = EmployeeAction::findOrfail($id);
        return new EmployeeActionShowResource($employeeAction);
    }

    /**
     * @OA\Put(
     *      path="api/employee_action/{id}",
     *      tags={"Данные Предпринятыx действий сотрудников Акыйкатчы"},
     *      summary="Обновление Предпринятыx действий сотрудников Акыйкатчы",
     *      description="Обновление Предпринятыx действий сотрудников Акыйкатчы",
     *          @OA\RequestBody(
     *              required=true,
     *              @OA\JsonContent(ref="#/components/schemas/EmployeeActionStoreRequest")
     *           ),
     *          @OA\Parameter(
     *              name="id",
     *              description="id данных",
     *              required=true,
     *              in="path",
     *              @OA\Schema(
     *                  type="integer"
     *              )
     *          ),
     *          @OA\Response(
     *              response=200,
     *              description="Successful operation",
     *              @OA\JsonContent(ref="#/components/schemas/EmployeeActionShowResource")
     *          ),
     *          @OA\Response(
     *              response=401,
     *              description="Unauthenticated",
     *          ),
     *          @OA\Response(
     *              response=419,
     *              description="CSRF token mismatch"
     *          ),
     *          @OA\Response(
     *              response=404,
     *              description="Not Found"
     *          ),
     *          @OA\Response(
     *              response=500,
     *              description="Server Error"
     *          ),
     *          @OA\Response(
     *              response=403,
     *              description="Forbidden"
     *          )
     *     )
     */

    public function update(EmployeeActionStoreRequest $request, $id)
    {
        $data = $request->validated();
        $employeeAction = EmployeeAction::findOrfail($id);

        $employeeAction->update([
            'case_id' => $data['case_id'],
            'government_agency_id' => $data['government_agency_id'],
            'note' => $data['note'],
            'action_to_violator_id' => $data['action_to_violator_id'],
            'document_id' => $data['document_id'],
            'date' => $data['date'],
            'defendants' => json_encode($data['defendants']),
            'appeal_id' => $data['appeal_id'] ?? null,
        ]);

        return (new EmployeeActionShowResource($employeeAction))
            ->response()
            ->setStatusCode(Response::HTTP_OK);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function destroy($id)
    {
        $employeeAction = EmployeeAction::findOrfail($id);
        $case = $employeeAction->case;
        $employeeAction->delete();

        $employeeActions = $case->employeeActions()->get();

        return EmployeeActionShowResource::collection($employeeActions);
    }
}
