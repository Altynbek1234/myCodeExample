<?php

namespace App\Http\Controllers\References;

use App\Http\Controllers\Controller;
use App\Http\Resources\References\ReasonForRejectingResource;
use App\Models\ReasonForRejecting;
use Symfony\Component\HttpFoundation\Response;

class ReasonForRejectingController extends Controller
{
    //Причины отклонения обращения
    /**
     * @OA\Get(
     *      path="api/reference/reason_for_rejecting",
     *      tags={"Cправочники"},
     *      summary="Причины отклонения обращения",
     *      description="Причины отклонения обращения",
     *          @OA\Response(
     *              response="200",
     *              description="Returns the  collection",
     *              @OA\JsonContent(
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/ReasonForRejectingResource")
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
        $reasonForRejecting = ReasonForRejecting::where('status_id', 1)
            ->paginate(10);
        return (ReasonForRejectingResource::collection($reasonForRejecting))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }
}
