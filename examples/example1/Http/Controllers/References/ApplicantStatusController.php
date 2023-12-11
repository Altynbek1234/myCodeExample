<?php

namespace App\Http\Controllers\References;

use App\Http\Controllers\Controller;
use App\Http\Resources\References\ApplicantStatusResource;
use App\Models\ApplicantStatus;
use Symfony\Component\HttpFoundation\Response;

class ApplicantStatusController extends Controller
{
    /**
     * @OA\Get(
     *      path="api/reference/applicant_statuses",
     *      tags={"Cправочники"},
     *      summary="Статус заявителя",
     *      description="Статус заявителя",
     *          @OA\Response(
     *              response="200",
     *              description="Returns the  collection",
     *              @OA\JsonContent(
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/ApplicantStatusResource")
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
        $applicantStatus = ApplicantStatus::paginate(10);
        return (ApplicantStatusResource::collection($applicantStatus))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }
}
