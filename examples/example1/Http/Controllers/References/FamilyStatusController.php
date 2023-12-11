<?php

namespace App\Http\Controllers\References;

use App\Http\Controllers\Controller;
use App\Http\Resources\References\FamilyStatusResource;
use App\Models\FamilyStatus;
use Symfony\Component\HttpFoundation\Response;

class FamilyStatusController extends Controller
{
    /**
     * @OA\Get(
     *      path="api/reference/family_status",
     *      tags={"Cправочники"},
     *      summary="Семейное положение",
     *      description="Семейное положение",
     *          @OA\Response(
     *              response="200",
     *              description="Returns the  collection",
     *              @OA\JsonContent(
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/FamilyStatusResource")
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
        $familyStatus = FamilyStatus::where('status_id', 1)
            ->paginate(10);
        return (FamilyStatusResource::collection($familyStatus))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }
}
