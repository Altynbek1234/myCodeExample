<?php

namespace App\Http\Controllers\References;

use App\Http\Controllers\Controller;
use App\Http\Resources\References\StatusReferenceResource;
use App\Models\StatusReference;
use Symfony\Component\HttpFoundation\Response;

class StatusReferenceController extends Controller
{
    /**
     * @OA\Get(
     *      path="api/reference/status_reference",
     *      tags={"Cправочники"},
     *      summary="Cтатус заявителя",
     *      description="Cтатус заявителя",
     *          @OA\Response(
     *              response="200",
     *              description="Returns the  collection",
     *              @OA\JsonContent(
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/StatusReferenceResource")
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
        $statusReference = StatusReference::paginate(10);
        return (StatusReferenceResource::collection($statusReference))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }
}
