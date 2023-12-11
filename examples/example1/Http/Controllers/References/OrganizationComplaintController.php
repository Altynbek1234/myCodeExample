<?php

namespace App\Http\Controllers\References;

use App\Http\Controllers\Controller;
use App\Http\Resources\References\OrganizationComplaintResource;
use App\Models\OrganizationComplaint;
use Symfony\Component\HttpFoundation\Response;

class OrganizationComplaintController extends Controller
{
    //Виды организаций, в отношении которых поступают жалобы
    /**
     * @OA\Get(
     *      path="api/reference/organization_complaint",
     *      tags={"Cправочники"},
     *      summary="Виды организаций, в отношении которых поступают жалобы",
     *      description="Виды организаций, в отношении которых поступают жалобы",
     *          @OA\Response(
     *              response="200",
     *              description="Returns the  collection",
     *              @OA\JsonContent(
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/OrganizationComplaintResource")
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
        $organizationComplaint = OrganizationComplaint::where('status_id', 1)
            ->paginate(10);
        return (OrganizationComplaintResource::collection($organizationComplaint))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }
}
