<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApplicantIndividual\ApplicantIndivAddressRequest;
use App\Http\Requests\ApplicantIndividual\ApplicantIndivRequest;
use App\Http\Resources\ApplicantIndividual\ItemAIAddressResource;
use App\Http\Resources\ApplicantIndividual\ItemAIResource;
use App\Models\ApplicantIndividual;
use App\Models\PersonInfo;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Crypt;

class ApplicantIndividualController extends Controller
{
    /**
     * @OA\Get(
     *      path="api/applicant-individual",
     *      tags={"Заявитель физ лицо"},
     *      summary="Список заявителей ",
     *      description="Получение заявителя",
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
     *      path="/api/applicant-individual",
     *      operationId="2",
     *      tags={"Заявитель физ лицо"},
     *      summary="Создание заявителя физ лицо",
     *      description="Создание заявителя физ лицо",
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
     *      path="api/applicant-individual/{id}",
     *      tags={"Заявитель физ лицо"},
     *      summary="Получение заявителя",
     *      description="Получение заявителя",
     *      @OA\Parameter(
     *              name="id",
     *              description="id заявителя",
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
     * @OA\Get(
     *      path="api/applicant-individual/{id}/address",
     *      tags={"Адреса и дополнительные данные"},
     *      summary="Получение адресов и дополнительных данных физ лица",
     *      @OA\Parameter(
     *              name="id",
     *              description="id физ лица",
     *              required=true,
     *              in="path",
     *              @OA\Schema(
     *                  type="integer"
     *              )
     *          ),
     *          @OA\Response(
     *              response=200,
     *              description="Successful operation",
     *              @OA\JsonContent(ref="#/components/schemas/AIlAddressResource")
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
    public function showAddress($id)
    {
        $appllicant = ApplicantIndividual::findOrfail($id);

        return new ItemAIAddressResource($appllicant);
    }

    /**
     * @OA\Put(
     *      path="api/applicant-individual/{id}",
     *      tags={"Заявитель физ лицо"},
     *      summary="Обновление данных  физ лицо заявителя",
     *      description="Обновление данных  физ лицо заявителя",
     *          @OA\RequestBody(
     *              required=true,
     *              @OA\JsonContent(ref="#/components/schemas/ApplicantIndividualRequest")
     *           ),
     *          @OA\Parameter(
     *              name="id",
     *              description="id заявителя",
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
        $applicantIndividual->note = $request->get('note');
        $applicantIndividual->status_id = $request->get('status_id');
        $applicantIndividual->save();

        return (new ItemAIResource($applicantIndividual))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     *      path="api/applicant-individual/{id}",
     *      tags={"Адреса и дополнительные данные"},
     *      summary="Добавление/обновление данных по адресам физ лица",
     *      description="Добавление/обновление данных по адресам физ лица",
     *          @OA\RequestBody(
     *              required=true,
     *              @OA\JsonContent(ref="#/components/schemas/ApplicantIndividualAddressRequest")
     *           ),
     *          @OA\Parameter(
     *              name="id",
     *              description="id физического лица, которому принадлежат адреса",
     *              required=true,
     *              in="path",
     *              @OA\Schema(
     *                  type="integer"
     *              )
     *          ),
     *          @OA\Response(
     *              response=200,
     *              description="Successful operation",
     *              @OA\JsonContent(ref="#/components/schemas/AIlAddressResource")
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
    public function updateAddress(ApplicantIndivAddressRequest $request, $id)
    {
        $request->validated();
        $applicantIndividual = ApplicantIndividual::findOrfail($id);

        $applicantIndividual->place_work_study = $request->get('place_work_study');
        $applicantIndividual->position = $request->get('position');
        $applicantIndividual->registration_address = $request->get('registration_address');
        $applicantIndividual->postal_address = $request->get('postal_address');
        $applicantIndividual->soate_registration_address = $request->get('soate_registration_address');
        $applicantIndividual->soate_postal_address = $request->get('soate_postal_address');
        $applicantIndividual->save();

        return (new ItemAIAddressResource($applicantIndividual))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * @OA\Delete(
     *      path="api/applicant-individual/{id}",
     *      tags={"Заявитель физ лицо"},
     *      summary="Удаление заявителя",
     *      description="Удаление заявителя",
     *          @OA\Response(
     *              response=204,
     *              description="Successful operation",
     *              @OA\JsonContent()
     *          ),
     *          @OA\Parameter(
     *              name="id",
     *              description="id заявителя",
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
