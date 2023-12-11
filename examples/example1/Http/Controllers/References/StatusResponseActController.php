<?php

namespace App\Http\Controllers\References;

use App\Http\Controllers\Controller;
use App\Http\Resources\References\StatusResponseActResource;
use App\Models\StatusResponseAct;
use Symfony\Component\HttpFoundation\Response;

class StatusResponseActController extends Controller
{
    //Статусы актов реагирования
    /**
     * @OA\Get(
     *      path="api/reference/status_response_act",
     *      tags={"Cправочники"},
     *      summary="Статусы актов реагирования",
     *      description="Статусы актов реагирования",
     *          @OA\Response(
     *              response="200",
     *              description="Returns the  collection",
     *              @OA\JsonContent(
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/StatusResponseActResource")
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
        $statusResponseAct = StatusResponseAct::paginate(10);
        return (StatusResponseActResource::collection($statusResponseAct))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }
}
