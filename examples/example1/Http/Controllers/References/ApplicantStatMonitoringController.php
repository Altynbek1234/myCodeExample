<?php

namespace App\Http\Controllers\References;

use App\Http\Controllers\Controller;
use App\Http\Resources\References\ApplicantStatMonitoringResource;
use App\Models\ApplicantStatMonitoring;
use Symfony\Component\HttpFoundation\Response;

class ApplicantStatMonitoringController extends Controller
{

    /**
     * @OA\Get(
     *      path="api/reference/applicant_stat_monitoring",
     *      tags={"Cправочники"},
     *      summary="Статусы заявителя на мониторинг судебных процессов",
     *      description="Статусы заявителя на мониторинг судебных процессов",
     *          @OA\Response(
     *              response="200",
     *              description="Returns the  collection",
     *              @OA\JsonContent(
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/ApplicantStatMonitoringResource")
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
        $applicantStatMonitoring = ApplicantStatMonitoring::all();
        return (ApplicantStatMonitoringResource::collection($applicantStatMonitoring))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }
}
