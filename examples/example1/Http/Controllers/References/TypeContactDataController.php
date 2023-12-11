<?php

namespace App\Http\Controllers\References;

use App\Http\Controllers\Controller;
use App\Http\Resources\References\TypeContactDataResource;
use App\Models\TypeContactData;
use Symfony\Component\HttpFoundation\Response;

class TypeContactDataController extends Controller
{
    /**
     * @OA\Get(
     *      path="api/reference/type_contact_data",
     *      tags={"Cправочники"},
     *      summary="Тип контактных данных",
     *      description="Тип контактных данных",
     *          @OA\Response(
     *              response="200",
     *              description="Returns the  collection",
     *              @OA\JsonContent(
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/TypeContactDataResource")
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
        $typeContactData = TypeContactData::paginate(10);
        return (TypeContactDataResource::collection($typeContactData))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }
}
