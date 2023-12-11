<?php

namespace App\Http\Controllers;

use App\Http\Requests\Court\CourtRequest;
use App\Http\Resources\Court\CourtResource;
use App\Models\Court;

class CourtController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/courts",
     *     tags={"Судебные процессы"},
     *     summary="Создание судебного процесса",
     *     operationId="store",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CourtStoreRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Created",
     *         @OA\JsonContent(ref="#/components/schemas/CourtShowResource")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Entity",
     *     )
     * )
     */
    public function store(CourtRequest $request)
    {
        $cort = Court::create($request->validated());

        return new CourtResource($cort);
    }

    /**
     * @OA\Put(
     *     path="/api/courts/{id}",
     *     tags={"Судебные процессы"},
     *     summary="Обновление судебного процесса",
     *     operationId="update",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Идентификатор",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CourtStoreRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(ref="#/components/schemas/CourtShowResource")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Entity",
     *     )
     * )
     */
    public function update(CourtRequest $request, Court $cort)
    {
        $cort->update($request->validated());

        return new CourtResource($cort);
    }

    /**
     * @OA\Delete(
     *     path="/api/courts/{id}",
     *     tags={"Судебные процессы"},
     *     summary="Удаление судебного процесса",
     *     operationId="destroy",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Идентификатор",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="No Content"
     *     )
     * )
     */
    public function destroy(Court $cort)
    {
        $cort->delete();

        return response()->json(null, 204);
    }
}
