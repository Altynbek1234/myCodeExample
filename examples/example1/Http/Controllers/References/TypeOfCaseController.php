<?php

namespace App\Http\Controllers\References;

use App\Http\Controllers\Controller;
use App\Http\Resources\References\TypeOfCaseResource;
use App\Models\TypeOfCase;
use Symfony\Component\HttpFoundation\Response;

class TypeOfCaseController extends Controller
{
    /**
     * @OA\Get(
     *      path="api/reference/type_of_case",
     *      tags={"Cправочники"},
     *      summary="Тип дела",
     *      description="Тип дела",
     *          @OA\Response(
     *              response="200",
     *              description="Returns the  collection",
     *              @OA\JsonContent(
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/TypeOfCaseResource")
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
        $typeOfCase = TypeOfCase::paginate(10);
        return (TypeOfCaseResource::collection($typeOfCase))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }
}
