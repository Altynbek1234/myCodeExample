<?php

namespace App\Http\Controllers\References;

use App\Http\Controllers\Controller;
use App\Http\Resources\References\FrequencyOfAppealResource;
use App\Models\FrequencyOfAppeal;
use Symfony\Component\HttpFoundation\Response;

class FrequencyOfTreatmentController extends Controller
{

    //По периодичности обращений

    /**
     * @OA\Get(
     *      path="api/reference/frequency_of_treatment",
     *      tags={"Cправочники"},
     *      summary="Периодичность обращения",
     *      description="Периодичность обращения",
     *          @OA\Response(
     *              response="200",
     *              description="Returns the  collection",
     *              @OA\JsonContent(
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/FrequencyOfAppealResource")
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
        $typeOfAppeal = FrequencyOfAppeal::all();
        return (FrequencyOfAppealResource::collection($typeOfAppeal))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }
}
