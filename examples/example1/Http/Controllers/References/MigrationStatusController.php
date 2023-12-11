<?php

namespace App\Http\Controllers\References;

use App\Http\Controllers\Controller;
use App\Http\Resources\References\MigrationStatusResource;
use App\Models\MigrationStatus;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MigrationStatusController extends Controller
{
    //Миграционный статус
    /**
     * @OA\Get(
     *      path="api/reference/migration_status",
     *      tags={"Cправочники"},
     *      summary="Миграционный статус",
     *      description="Миграционный статус",
     *          @OA\Response(
     *              response="200",
     *              description="Returns the  collection",
     *              @OA\JsonContent(
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/MigrationStatusResource")
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
        $migration_status = MigrationStatus::where('status_id', 1)->get();;
        return (MigrationStatusResource::collection($migration_status))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }
}
