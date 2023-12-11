<?php

namespace App\Http\Controllers;

use App\Http\Requests\Applicant\ApplicantRequest;
use App\Models\ApplicantIndividual;
use App\Models\ApplicantLegalEntity;

class ApplicantController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/applicant/search",
     *     tags={"Общий по заявителям"},
     *     summary="Поиск заявителя",
     *     description="Поиск заявителя",
     *     operationId="search",
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search string",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="applicantIndividual",
     *                     type="array",
     *                     @OA\Items(
     *                         ref="#/components/schemas/ApplicantIndividualRequest"
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="applicantLegal",
     *                     type="array",
     *                     @OA\Items(
     *                         ref="#/components/schemas/AIlResource"
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Entity",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="The given data was invalid."
     *             ),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="search",
     *                     type="array",
     *                     @OA\Items(
     *                         type="string",
     *                         example="The search field is required."
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function search(ApplicantRequest $request)
    {
        $search = $request->input('search');
        $applicantIndividual = ApplicantIndividual::whereHas('personInfo', function ($query) use ($search) {
            $query->where('name', 'ilike', '%'.$search.'%')
                ->orWhere('last_name', 'ilike', '%'.$search.'%')
                ->orWhere('patronymic', 'ilike', '%'.$search.'%')
                ->orWhere('inn', 'ilike', '%'.$search.'%');
        })->with('personInfo')->get();
        $applicantLegal = ApplicantLegalEntity::where('name', 'like', '%'.$search.'%')->get();
        $data = [];
        foreach ($applicantIndividual as $item) {
            $item->type = 'individual';
            $data[] = $item;
        }

        foreach ($applicantLegal as $item) {
            $item->type = 'legal_entity';
            $data[] = $item;
        }

        return response()->json([
            'data' => $data,
        ]);
    }

}
