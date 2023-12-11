<?php

namespace App\Http\Controllers\References;

use App\Http\Controllers\Controller;
use App\Http\Resources\References\TypeSpecializedInstitutionResource;
use App\Models\TypeSpecializedInstitution;
use Symfony\Component\HttpFoundation\Response;

class TypeSpecializedInstitutionController extends Controller
{
    /**
     * @OA\Get(
     *      path="api/reference/type_specialized_institution",
     *      tags={"Cправочники"},
     *      summary="Виды специализированных учреждений",
     *      description="Виды специализированных учреждений",
     *          @OA\Response(
     *              response="200",
     *              description="Returns the  collection",
     *              @OA\JsonContent(
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/TypeSpecializedInstitutionResource")
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
        $typeSpecializedInstitution = TypeSpecializedInstitution::paginate(10);
        return (TypeSpecializedInstitutionResource::collection($typeSpecializedInstitution))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }
}
