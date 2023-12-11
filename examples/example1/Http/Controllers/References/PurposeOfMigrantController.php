<?php

namespace App\Http\Controllers\References;

use App\Http\Controllers\Controller;
use App\Http\Resources\References\PurposeOfMigrantResource;
use App\Models\PurposeOfMigrant;
use Symfony\Component\HttpFoundation\Response;

class PurposeOfMigrantController extends Controller
{

    //Миграционный статус
    /**
     * @OA\Get(
     *      path="api/reference/purpose_of_migrants",
     *      tags={"Cправочники"},
     *      summary="Цели выезда мигранта",
     *      description="Цели выезда мигранта",
     *          @OA\Response(
     *              response="200",
     *              description="Returns the  collection",
     *              @OA\JsonContent(
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/PurposeOfMigrantResource")
     *              )
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
        $purpose_of_migrant = PurposeOfMigrant::where('status_id', 1)->get();;
        return (PurposeOfMigrantResource::collection($purpose_of_migrant))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }
}
