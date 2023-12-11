<?php

namespace App\Http\Controllers\References;

use App\Http\Controllers\Controller;
use App\Http\Resources\References\GettingDisabilityResource;
use App\Http\Resources\References\IncomeLevelResource;
use App\Models\GettingDisability;
use App\Models\IncomeLevel;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IncomeLevelController extends Controller
{
    /**
     * @OA\Get(
     *      path="api/reference/income_level",
     *      tags={"Cправочники"},
     *      summary="Уровень дохода",
     *      description="Уровень дохода",
     *          @OA\Response(
     *              response="200",
     *              description="Returns the  collection",
     *              @OA\JsonContent(
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/IncomeLevelResource")
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
        $incomeLevel = IncomeLevel::where('status_id', 1)
            ->paginate(10);
        return (IncomeLevelResource::collection($incomeLevel))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }
}
