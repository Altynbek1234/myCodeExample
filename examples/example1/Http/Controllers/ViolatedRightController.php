<?php

namespace App\Http\Controllers;

use App\Http\Requests\ViolatedRight\ViolatedRightStoreRequest;
use App\Http\Resources\ViolatedRight\ViolatedRightShowResource;
use App\Models\EmployeeAction;
use App\Models\ViolatedRight;
use App\Services\NextCloudService;
use Illuminate\Http\Request;
use App\Http\Resources\Case\CaseShowResource;
use Symfony\Component\HttpFoundation\Response;




class ViolatedRightController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/violated-right",
     *     tags={"Данные Нарушенныx прав"},
     *     summary="Создание Нарушенныx прав",
     *     description="Создание Нарушенныx прав",
     *     operationId="violated-right.store",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ViolatedRightStoreRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(ref="#/components/schemas/ViolatedRightShowResource")
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

    public function store(ViolatedRightStoreRequest $request)
    {
        $data = $request->validated();
        $violatedRight = ViolatedRight::create([
            'case_id' => $data['case_id'],
            'government_agency_id' => $data['government_agency_id'],
            'note' => $data['note'],
            'violations_classifier_id' => $data['violations_classifier_id'],
            'defendants' => json_encode($data['defendants']),
            'appeal_id' => $data['appeal_id'] ?? null,
        ]);

        return (new ViolatedRightShowResource($violatedRight))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * @OA\Get(
     *      path="api/violated-right/{id}",
     *     tags={"Данные Нарушенныx прав"},
     *     summary="Получение Нарушенныx прав",
     *      description="Получение Нарушенныx прав",
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
     *         @OA\JsonContent(ref="#/components/schemas/ViolatedRightShowResource")
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
        $violatedRight = ViolatedRight::findOrfail($id);
        return new ViolatedRightShowResource($violatedRight);
    }

    /**
     * @OA\Put(
     *      path="api/violated-right/{id}",
     *      tags={"Данные Нарушенныx прав"},
     *      summary="Обновление Нарушенныx прав",
     *      description="Обновление Нарушенныx прав",
     *          @OA\RequestBody(
     *              required=true,
     *              @OA\JsonContent(ref="#/components/schemas/ViolatedRightStoreRequest")
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
     *              @OA\JsonContent(ref="#/components/schemas/ViolatedRightShowResource")
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

    public function update(ViolatedRightStoreRequest $request, $id)
    {

        $violatedRight = ViolatedRight::findOrfail($id);
        $data = $request->validated();

        $violatedRight->update([
            'case_id' => $data['case_id'],
            'government_agency_id' => $data['government_agency_id'],
            'note' => $data['note'],
            'violations_classifier_id' => $data['violations_classifier_id'],
            'defendants' => json_encode($data['defendants']),
            'appeal_id' => $data['appeal_id'] ?? null,
        ]);

        return (new ViolatedRightShowResource($violatedRight))
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
        $violatedRight = ViolatedRight::findOrfail($id);
        $case = $violatedRight->case;
        $violatedRight->delete();

        $violatedRights = $case->violatedRights()->get();

        return ViolatedRightShowResource::collection($violatedRights);
    }
}
