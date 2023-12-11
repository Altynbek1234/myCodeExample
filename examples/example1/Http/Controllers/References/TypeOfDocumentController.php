<?php

namespace App\Http\Controllers\References;

use App\Http\Controllers\Controller;
use App\Http\Resources\References\TypeOfDocumentResource;
use App\Models\TypeOfDocument;
use Symfony\Component\HttpFoundation\Response;

class TypeOfDocumentController extends Controller
{
    /**
     * @OA\Get(
     *      path="api/reference/type_of_document",
     *      tags={"Cправочники"},
     *      summary="Виды документов",
     *      description="Виды документов",
     *          @OA\Response(
     *              response=200,
     *              description="Success",
     *              @OA\JsonContent(ref="#/components/schemas/TypeOfDocumentsResource")
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
        $typeOfDocument = TypeOfDocument::paginate(10);
        return (TypeOfDocumentResource::collection($typeOfDocument))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }
}
