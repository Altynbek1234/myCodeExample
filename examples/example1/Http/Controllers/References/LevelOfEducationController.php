<?php

namespace App\Http\Controllers\References;

use App\Http\Controllers\Controller;
use App\Http\Resources\References\LevelOfEducationResource;
use App\Models\LevelOfEducation;
use Symfony\Component\HttpFoundation\Response;

class LevelOfEducationController extends Controller
{
    //Уровень образования
    /**
     * @OA\Get(
     *      path="api/reference/level_of_education",
     *      tags={"Cправочники"},
     *      summary="Уровень дохода",
     *      description="Уровень дохода",
     *          @OA\Response(
     *              response="200",
     *              description="Returns the  collection",
     *              @OA\JsonContent(
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/LevelOfEducationResource")
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
        $levelOfEducation = LevelOfEducation::where('status_id', 1)
            ->paginate(10);
        return (LevelOfEducationResource::collection($levelOfEducation))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }
}
