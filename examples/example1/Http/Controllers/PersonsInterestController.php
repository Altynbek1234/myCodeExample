<?php

namespace App\Http\Controllers;


use App\Http\Requests\ApplicantIndividual\ApplicantIndivRequest;
use App\Http\Requests\PersonsInterest\PersonInterestReqauest;
use App\Http\Resources\ApplicantIndividual\ItemAIResource;
use App\Http\Resources\PersonsInterest\PersonsInterestResource;
use App\Models\ApplicantIndividual;
use App\Models\PersonInterest;
use App\Models\PersonInfo;
use Illuminate\Support\Facades\Crypt;
use Symfony\Component\HttpFoundation\Response;

class PersonsInterestController extends Controller
{
    /**
     * @OA\Get(
     *      path="api/person-interest",
     *      tags={"Лица в интересах которых поступило обращение"},
     *      summary="Лица в интересах которых поступило обращение ",
     *      description="Получение лиц ",
     *          @OA\Response(
     *              response="200",
     *              description="Returns the  collection",
     *              @OA\JsonContent(
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/AIlResource")
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
        $apllicants = ApplicantIndividual::paginate(10);
        return (ItemAIResource::collection($apllicants))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     *      path="/api/person-interest",
     *      operationId="3",
     *      tags={"Лица в интересах которых поступило обращение"},
     *      summary="Создание лица в интересах которых поступило обращение",
     *      description="Создание лица в интересах которых поступило обращение",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/ApplicantIndividualRequest")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/AIlResource")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *           response=419,
     *           description="CSRF token mismatch"
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Server Error"
     *      ),
     *    )
     */
    public function store(ApplicantIndivRequest $request)
    {
        $request->validated();
        $personInfo = new PersonInfo();
        $personInfo->name = $request->get('name');
        $personInfo->last_name = $request->get('last_name');
        $personInfo->patronymic = $request->get('patronymic');
        $personInfo->inn = $request->get('inn');
        $personInfo->save();

        $applicantIndividual = new ApplicantIndividual();
        $applicantIndividual->person_info_id = $personInfo->id;
        $applicantIndividual->citizenship_id = $request->get('citizenship_id');
        $applicantIndividual->date_birth = $request->get('date_birth');
        $applicantIndividual->gender_id = $request->get('gender_id');
        $applicantIndividual->place_work_study = $request->get('place_work_study');
        $applicantIndividual->position = $request->get('position');
        $applicantIndividual->registration_address = $request->get('registration_address');
        $applicantIndividual->postal_address = $request->get('postal_address');
        $applicantIndividual->note = $request->get('note');
        $applicantIndividual->status_id = $request->get('status_id');
        $applicantIndividual->save();

        return (new ItemAIResource($applicantIndividual))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * @OA\Get(
     *      path="api/person-interest/{id}",
     *      tags={"Лица в интересах которых поступило обращение"},
     *      summary="Получение лица в интересах которых поступило обращение",
     *      description="Лица в интересах которых поступило обращение",
     *      @OA\Parameter(
     *              name="id",
     *              description="id лица",
     *              required=true,
     *              in="path",
     *              @OA\Schema(
     *                  type="integer"
     *              )
     *          ),
     *          @OA\Response(
     *              response=200,
     *              description="Successful operation",
     *              @OA\JsonContent(ref="#/components/schemas/AIlResource")
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
    public function show($id)
    {
        $apllicant = ApplicantIndividual::findOrfail($id);
        return new ItemAIResource($apllicant);
    }

    /**
     * @OA\Put(
     *      path="api/person-interest/{id}",
     *      tags={"Лица в интересах которых поступило обращение"},
     *      summary="Обновление данных лица в интересах которых поступило обращение ",
     *      description="Обновление данных",
     *          @OA\RequestBody(
     *              required=true,
     *              @OA\JsonContent(ref="#/components/schemas/PersonInterestReqauest")
     *           ),
     *          @OA\Parameter(
     *              name="id",
     *              description="id лица",
     *              required=true,
     *              in="path",
     *              @OA\Schema(
     *                  type="integer"
     *              )
     *          ),
     *          @OA\Response(
     *              response=200,
     *              description="Successful operation",
     *              @OA\JsonContent(ref="#/components/schemas/PersonsInterestResource")
     *          ),
     *          @OA\Response(
     *              response=401,
     *              description="Unauthenticated",
     *          ),
     *          @OA\Response(
     *              response=419,
     *              description="CSRF token mismatch"
     *          ),
     *          @OA\Response(
     *              response=404,
     *              description="Not Found"
     *          ),
     *          @OA\Response(
     *              response=500,
     *              description="Server Error"
     *          ),
     *          @OA\Response(
     *              response=403,
     *              description="Forbidden"
     *          )
     *     )
     */
    public function update(ApplicantIndivRequest $request, $id)
    {
        $request->validated();
        $applicantIndividual = ApplicantIndividual::findOrfail($id);
        $personInfo = PersonInfo::findOrfail($applicantIndividual->person_info_id);
        $personInfo->name = $request->get('name');
        $personInfo->last_name = $request->get('last_name');
        $personInfo->patronymic = $request->get('patronymic');
        $personInfo->inn = $request->get('inn');
        $personInfo->save();

        $applicantIndividual->person_info_id = $personInfo->id;
        $applicantIndividual->citizenship_id = $request->get('citizenship_id');
        $applicantIndividual->date_birth = $request->get('date_birth');
        $applicantIndividual->gender_id = $request->get('gender_id');
        $applicantIndividual->place_work_study = $request->get('place_work_study');
        $applicantIndividual->position = $request->get('position');
        $applicantIndividual->registration_address = $request->get('registration_address');
        $applicantIndividual->postal_address = $request->get('postal_address');
        $applicantIndividual->soate_id = $request->get('soate_id');
        $applicantIndividual->note = $request->get('note');
        $applicantIndividual->status_id = $request->get('status_id');
        $applicantIndividual->save();

        return (new ItemAIResource($applicantIndividual))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * @OA\Delete(
     *      path="api/person-interest/{id}",
     *      tags={"Лица в интересах которых поступило обращение"},
     *      summary="Удаление лица в интересах ",
     *      description="Удаление лица в интересах",
     *          @OA\Response(
     *              response=204,
     *              description="Successful operation",
     *              @OA\JsonContent()
     *          ),
     *          @OA\Parameter(
     *              name="id",
     *              description="id лица",
     *              required=true,
     *              in="path",
     *              @OA\Schema(
     *                  type="integer"
     *              )
     *          ),
     *          @OA\Response(
     *              response=401,
     *              description="Unauthenticated",
     *          ),
     *          @OA\Response(
     *              response=419,
     *              description="CSRF token mismatch"
     *          ),
     *          @OA\Response(
     *              response=404,
     *              description="Not Found"
     *          ),
     *          @OA\Response(
     *              response=500,
     *              description="Server Error"
     *          ),
     *          @OA\Response(
     *              response=403,
     *              description="Forbidden"
     *          )
     *     )
     */
    public function destroy($id)
    {
        $applicantIndividual = ApplicantIndividual::findOrfail($id);
        $personInfo = PersonInfo::findOrfail($applicantIndividual->person_info_id);
        $applicantIndividual->delete();
        $personInfo->delete();
        return response()->json(null, 204);
    }
}
