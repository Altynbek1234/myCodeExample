<?php

namespace App\Http\Controllers\References;

use App\Http\Controllers\Controller;
use App\Http\Resources\References\BranchOfLawResource;
use App\Models\BranchOfLaw;
use Symfony\Component\HttpFoundation\Response;

class BranchOfLawController extends Controller
{
    /**
     * @OA\Get(
     *      path="api/reference/branch_of_law",
     *      tags={"Cправочники"},
     *      summary="Отрасли права",
     *      description="Отрасли права",
     *          @OA\Response(
     *              response="200",
     *              description="Returns the  collection",
     *              @OA\JsonContent(
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/BranchOfLawResource")
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
        $branchOfLaw = BranchOfLaw::where('status_id', 1)
            ->paginate(10);
        return (BranchOfLawResource::collection($branchOfLaw))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }
}
