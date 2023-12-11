<?php

namespace App\Http\Controllers\References;

use App\Http\Controllers\Controller;
use App\Http\Resources\References\AppealTypeResource;
use App\Models\AppealType;
use Symfony\Component\HttpFoundation\Response;

class AppealTypeController extends Controller
{

    /**
     * @OA\Get(
     *      path="api/reference/appeal_types",
     *      tags={"Cправочники"},
     *      summary="Типы обращения",
     *      description="Типы обращения",
     *          @OA\Response(
     *              response="200",
     *              description="Returns the  collection",
     *              @OA\JsonContent(
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/AppealTypeResource")
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
        $applicantType = AppealType::all();
        return (AppealTypeResource::collection($applicantType))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }
}
