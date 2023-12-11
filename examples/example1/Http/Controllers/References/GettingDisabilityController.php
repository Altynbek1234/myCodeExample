<?php

namespace App\Http\Controllers\References;

use App\Http\Controllers\Controller;
use App\Http\Resources\References\GettingDisabilityResource;
use App\Models\GettingDisability;
use Symfony\Component\HttpFoundation\Response;

class GettingDisabilityController extends Controller
{
    /**
     * @OA\Get(
     *      path="api/reference/getting_disability",
     *      tags={"Cправочники"},
     *      summary="Получение инвалидности",
     *      description="Получение инвалидности",
     *          @OA\Response(
     *              response="200",
     *              description="Returns the  collection",
     *              @OA\JsonContent(
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/GettingDisabilityResource")
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
        $gettingDisability = GettingDisability::where('status_id', 1)
            ->paginate(10);
        return (GettingDisabilityResource::collection($gettingDisability))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }
}
