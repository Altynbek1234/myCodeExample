<?php

namespace App\Http\Controllers\References;

use App\Http\Controllers\Controller;
use App\Http\Resources\References\TypeOfAppealResource;
use App\Models\TypeOfAppeal;
use Symfony\Component\HttpFoundation\Response;

class TypeOfAppealController extends Controller
{
    //По типу обращений
    /**
     * @OA\Get(
     *      path="api/reference/type_of_appeal",
     *      tags={"Cправочники"},
     *      summary="Виды обращений",
     *      description="Виды обращений",
     *          @OA\Response(
     *              response="200",
     *              description="Returns the  collection",
     *              @OA\JsonContent(
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/TypeOfAppealsResource")
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
        $typeOfAppeal = TypeOfAppeal::paginate(10);
        return (TypeOfAppealResource::collection($typeOfAppeal))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }
}
