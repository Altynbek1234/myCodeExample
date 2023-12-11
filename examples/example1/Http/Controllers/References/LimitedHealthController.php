<?php

namespace App\Http\Controllers\References;

use App\Http\Controllers\Controller;
use App\Http\Resources\References\LimitedHealthResource;
use App\Models\LimitedHealth;
use Symfony\Component\HttpFoundation\Response;

class LimitedHealthController extends Controller
{
    //Сведения о лицах с инвалидностью
    /**
     * @OA\Get(
     *      path="api/reference/limited_health",
     *      tags={"Cправочники"},
     *      summary="Сведения о лицах с инвалидностью",
     *      description="Сведения о лицах с инвалидностью",
     *          @OA\Response(
     *              response="200",
     *              description="Returns the  collection",
     *              @OA\JsonContent(
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/LimitedHealthResource")
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
        $limitedHealth = LimitedHealth::where('status_id', 1)
            ->paginate(10);
        return (LimitedHealthResource::collection($limitedHealth))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }
}
