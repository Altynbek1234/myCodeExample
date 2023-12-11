<?php

namespace App\Http\Controllers\References;

use App\Http\Controllers\Controller;
use App\Http\Resources\References\StatusStatedFactResource;
use App\Models\StatusStatedFact;
use Symfony\Component\HttpFoundation\Response;

class StatusStatedFactActController extends Controller
{
    //Статус изложенных фактов
    /**
     * @OA\Get(
     *      path="api/reference/status_stated_fact",
     *      tags={"Cправочники"},
     *      summary="Статус изложенных фактов",
     *      description="Статус изложенных фактов",
     *          @OA\Response(
     *              response="200",
     *              description="Returns the  collection",
     *              @OA\JsonContent(
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/StatusStatedFactResource")
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
        $statusStatedFact = StatusStatedFact::paginate(10);
        return (StatusStatedFactResource::collection($statusStatedFact))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }
}
