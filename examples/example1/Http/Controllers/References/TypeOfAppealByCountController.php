<?php

namespace App\Http\Controllers\References;

use App\Http\Controllers\Controller;
use App\Http\Resources\References\TypeOfAppealByCountResource;
use App\Models\TypeOfAppealByCount;
use Symfony\Component\HttpFoundation\Response;

class TypeOfAppealByCountController extends Controller
{
    //Виды обращений по количеству заявителей
    /**
     * @OA\Get(
     *      path="api/reference/type_of_appeal_by_count",
     *      tags={"Cправочники"},
     *      summary="Виды обращений по количеству заявителей",
     *      description="Виды обращений по количеству заявителе",
     *          @OA\Response(
     *              response="200",
     *              description="Returns the  collection",
     *              @OA\JsonContent(
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/TypeOfAppealsByCountResource")
     *              )
     *          ),
     *          @OA\Parameter(
     *               name="page",
     *               description="Page number",
     *               required=false,
     *               in="path",
     *               @OA\Schema(
     *                   type="integer"
     *               )
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
    public function index()
    {
        $typeOfAppealByCount = TypeOfAppealByCount::paginate(10);
        return (TypeOfAppealByCountResource::collection($typeOfAppealByCount))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }
}
