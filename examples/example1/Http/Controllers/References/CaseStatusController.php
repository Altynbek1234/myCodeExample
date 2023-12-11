<?php

namespace App\Http\Controllers\References;

use App\Http\Controllers\Controller;
use App\Http\Resources\References\CaseStatusResource;

use App\Models\CaseStatus;

use Symfony\Component\HttpFoundation\Response;

class CaseStatusController extends Controller
{
    /**
     * @OA\Get(
     *      path="api/reference/case_status",
     *      tags={"Cправочники"},
     *      summary="Статус дела",
     *      description="Статус дела",
     *          @OA\Response(
     *              response="200",
     *              description="Returns the  collection",
     *              @OA\JsonContent(
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/CaseStatusResource")
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
        $caseStatus = CaseStatus::where('status_id', 1)
            ->paginate(10);
        return (CaseStatusResource::collection($caseStatus))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }
}
