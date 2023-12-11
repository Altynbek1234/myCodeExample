<?php

namespace App\Http\Controllers\References;

use App\Http\Controllers\Controller;
use App\Http\Resources\References\CharacterOfQuestionResource;
use App\Models\CharacterOfQuestion;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CharacterOfQuestionController extends Controller
{
    /**
     * @OA\Get(
     *      path="api/reference/сharacter_of_question",
     *      tags={"Cправочники"},
     *      summary="Характер вопроса",
     *      description="Характер вопроса",
     *          @OA\Response(
     *              response="200",
     *              description="Returns the  collection",
     *              @OA\JsonContent(
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/CharacterOfQuestionResource")
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
        $characterOfQuestion = CharacterOfQuestion::where('status_id', 1)
            ->paginate(10);
        return (CharacterOfQuestionResource::collection($characterOfQuestion))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }
}
