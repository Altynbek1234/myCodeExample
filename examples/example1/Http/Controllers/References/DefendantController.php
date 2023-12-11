<?php

namespace App\Http\Controllers\References;

use App\Http\Controllers\Controller;
use App\Http\Requests\References\Defendent\SearchRequest;
use App\Http\Requests\References\Defendent\StoreRequest;
use App\Http\Requests\References\Defendent\UpdateRequest;
use App\Http\Resources\References\DefendantResource;
use App\Models\Defendant;

class DefendantController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/defendents",
     *     tags={"Гос.служащие"},
     *     summary="Список гос.служащих",
     *     description="Список гос.служащих",
     *     operationId="defendentsIndex",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/DefendentShowResource")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
    public function index()
    {
        $defendents = Defendant::all();

        return DefendantResource::collection($defendents);
    }

    /**
     * @OA\Get(
     *     path="/api/defendents/search",
     *     tags={"Гос.служащие"},
     *     summary="Поиск гос.служащих",
     *     description="Поиск гос.служащих",
     *     operationId="defendentsSearch",
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search string",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             example="Иванов"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/DefendentShowResource")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
    public function search(SearchRequest $request)
    {
        $defendents = Defendant::where('name', 'like', '%' . $request->search . '%')
            ->orWhere('last_name', 'ilike', '%' . $request->search . '%')
            ->orWhere('patronymic', 'ilike', '%' . $request->search . '%')
            ->orWhere('inn', 'ilike', '%' . $request->search . '%')
            ->get();

        return DefendantResource::collection($defendents);
    }

    /**
     * @OA\Post(
     *     path="/api/defendents",
     *     tags={"Гос.служащие"},
     *     summary="Создать гос.служащего",
     *     description="Создать гос.служащего",
     *     operationId="defendentsStore",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/DefendentStoreRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/DefendentShowResource")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
    public function store(StoreRequest $request)
    {
        $validated = $request->validated();
        $defendent = Defendant::create($validated);

        return new DefendantResource($defendent);
    }

    /**
     * @OA\Get(
     *     path="/api/defendents/{id}",
     *     tags={"Гос.служащие"},
     *     summary="Данные гос.служащего",
     *     description="Данные гос.служащего",
     *     operationId="defendentsShow",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Defendant id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             example=1
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/DefendentShowResource")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not found",
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
    public function show(Defendant $defendent)
    {
        return new DefendantResource($defendent);
    }

    /**
     * @OA\Put(
     *     path="/api/defendents/{id}",
     *     tags={"Гос.служащие"},
     *     summary="Обновить данные гос.служащего",
     *     description="Обновить данные гос.служащего",
     *     operationId="defendentsUpdate",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Defendant id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             example=1
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/DefendentStoreRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/DefendentShowResource")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not found",
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
    public function update(UpdateRequest $request, Defendant $defendent)
    {
        $validated = $request->validated();

        $defendent->update($validated);

        return new DefendantResource($defendent);
    }

    /**
     * @OA\Delete(
     *     path="/api/defendents/{id}",
     *     tags={"Гос.служащие"},
     *     summary="Удалить гос.служащего",
     *     description="Удалить гос.служащего",
     *     operationId="defendentsDestroy",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Defendant id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             example=1
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Successful operation",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not found",
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
    public function destroy(Defendant $defendent)
    {
        $defendent->delete();

        return response()->json(null, 204);
    }
}
