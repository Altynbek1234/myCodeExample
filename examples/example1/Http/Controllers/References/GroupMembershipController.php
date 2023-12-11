<?php

namespace App\Http\Controllers\References;

use App\Http\Controllers\Controller;
use App\Http\Resources\References\GroupMembershipResource;
use App\Models\GroupMembership;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GroupMembershipController extends Controller
{

    //Миграционный статус
    /**
     * @OA\Get(
     *      path="api/reference/group_memberships",
     *      tags={"Cправочники"},
     *      summary="Принадлежность к группе",
     *      description="Принадлежность к группе",
     *          @OA\Response(
     *              response="200",
     *              description="Returns the  collection",
     *              @OA\JsonContent(
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/GroupMembershipResource")
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
        $group_memberships = GroupMembership::where('status_id', 1)->get();;
        return (GroupMembershipResource::collection($group_memberships))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }
}
