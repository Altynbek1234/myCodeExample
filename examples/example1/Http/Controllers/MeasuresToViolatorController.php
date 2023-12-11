<?php

namespace App\Http\Controllers;

use App\Http\Requests\MeasuresToViolator\MeasuresToViolatorStoreRequest;
use App\Http\Resources\MeasuresToViolator\MeasuresToViolatorShowResource;
use App\Models\MeasuresToViolator;
use App\Services\NextCloudService;
use Illuminate\Http\Request;
use App\Http\Resources\Case\CaseShowResource;
use Symfony\Component\HttpFoundation\Response;




class MeasuresToViolatorController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/measures_to_violator",
     *     tags={"Данные Предпринятыx мер к нарушителям"},
     *     summary="Создание Предпринятыx мер к нарушителям",
     *     description="Создание Предпринятыx мер к нарушителям",
     *     operationId="measures_to_violator.store",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/MeasuresToViolatorStoreRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(ref="#/components/schemas/MeasuresToViolatorShowResource")
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

    public function store(MeasuresToViolatorStoreRequest $request)
    {
        $measuresToViolator = MeasuresToViolator::create($request->validated());

        return (new MeasuresToViolatorShowResource($measuresToViolator))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);

    }

    /**
     * @OA\Get(
     *      path="api/measures_to_violator/{id}",
     *     tags={"Данные Предпринятыx мер к нарушителям"},
     *     summary="Получение Предпринятыx мер к нарушителям",
     *      description="Получение Предпринятыx мер к нарушителям",
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
     *         @OA\JsonContent(ref="#/components/schemas/MeasuresToViolatorShowResource")
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
        $measuresToViolator = MeasuresToViolator::findOrfail($id);
        return new MeasuresToViolatorShowResource($measuresToViolator);
    }

    /**
     * @OA\Put(
     *      path="api/measures_to_violator/{id}",
     *      tags={"Данные Предпринятыx мер к нарушителям"},
     *      summary="Обновление Предпринятыx мер к нарушителям",
     *      description="Обновление Предпринятыx мер к нарушителям",
     *          @OA\RequestBody(
     *              required=true,
     *              @OA\JsonContent(ref="#/components/schemas/MeasuresToViolatorStoreRequest")
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
     *              @OA\JsonContent(ref="#/components/schemas/MeasuresToViolatorShowResource")
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

    public function update(MeasuresToViolatorStoreRequest $request, $id)
    {

        $measuresToViolator = MeasuresToViolator::findOrfail($id);

        $measuresToViolator->update($request->validated());

        return (new MeasuresToViolatorShowResource($measuresToViolator))
            ->response()
            ->setStatusCode(Response::HTTP_OK);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $measuresToViolator = MeasuresToViolator::findOrfail($id);
        $measuresToViolator->delete();

        return response()->json(['message' => 'Violated Right deleted successfully'], 200);
    }
}
