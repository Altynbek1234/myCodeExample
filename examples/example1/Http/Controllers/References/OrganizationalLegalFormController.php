<?php

namespace App\Http\Controllers\References;

use App\Http\Controllers\Controller;
use App\Http\Resources\References\OrganizationalLegalFormResource;
use App\Models\OrganizationalLegalForm;
use Symfony\Component\HttpFoundation\Response;

class OrganizationalLegalFormController extends Controller
{
    //Государственный классификатор КР Организационно-правовые формы
    /**
     * @OA\Get(
     *      path="api/reference/organizational_legal_form",
     *      tags={"Cправочники"},
     *      summary="Государственный классификатор КР Организационно-правовые формы",
     *      description="Государственный классификатор КР Организационно-правовые формы",
     *          @OA\Response(
     *              response="200",
     *              description="Returns the  collection",
     *              @OA\JsonContent(
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/OrganizationalLegalFormResource")
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
        $organizationalLegalForm = OrganizationalLegalForm::where('status_id', 1)
            ->paginate(10);
        return (OrganizationalLegalFormResource::collection($organizationalLegalForm))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }
}
