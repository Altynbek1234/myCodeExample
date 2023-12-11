<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApplicantLegalEntity\ApplicantLEAddressRequest;
use App\Http\Requests\ApplicantLegalEntity\ApplicantLERequest;
use App\Http\Resources\ApplicantLegalEntity\ItemALEAddressResource;
use App\Http\Resources\ApplicantLegalEntity\ItemALEResource;
use App\Models\ApplicantLegalEntity as ApplicantLegalEntity;
use Symfony\Component\HttpFoundation\Response;

class ApplicantLegalEntityController extends Controller
{
    /**
     * @OA\Get(
     *      path="api/applicant-legal-entity",
     *      tags={"Заявитель юр лицо"},
     *      summary="Список заявителей ",
     *      description="Получение заявителя",
     *          @OA\Response(
     *              response="200",
     *              description="Returns the  collection",
     *              @OA\JsonContent(
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/ALEResource")
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
        $apllicants = ApplicantLegalEntity::paginate(10);
        return (ItemALEResource::collection($apllicants))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     *      path="/api/applicant-legal-entity",
     *      operationId="1",
     *      tags={"Заявитель юр лицо"},
     *      summary="Создание заявителя юр лицо",
     *      description="Создание заявителя юр лицо",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/ApplicantLERequest")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/ALEResource")
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
    public function store(ApplicantLERequest $request)
    {
        $data = $request->validated();
        $apllicant = ApplicantLegalEntity::create($data);

        return (new ItemALEResource($apllicant))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * @OA\Get(
     *      path="api/applicant-legal-entity/{id}",
     *      tags={"Заявитель юр лицо"},
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
     *              @OA\JsonContent(ref="#/components/schemas/ALEResource")
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
        $apllicant = ApplicantLegalEntity::findOrfail($id);
        return new ItemALEResource($apllicant);
    }

    /**
     * @OA\Get(
     *      path="api/applicant-legal-entity/{id}/address",
     *      tags={"Адреса и дополнительные данные"},
     *      summary="Получение адресов и дополнительных данных юр лица",
     *      description="Получение адресов и дополнительных данных юр лица",
     *      @OA\Parameter(
     *              name="id",
     *              description="id юридического лица",
     *              required=true,
     *              in="path",
     *              @OA\Schema(
     *                  type="integer"
     *              )
     *          ),
     *          @OA\Response(
     *              response=200,
     *              description="Successful operation",
     *              @OA\JsonContent(ref="#/components/schemas/ALEAddressResource")
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
        $appllicant = ApplicantLegalEntity::findOrfail($id);

        return new ItemALEAddressResource($appllicant);
    }

    /**
     * @OA\Put(
     *      path="api/applicant-legal-entity/{id}",
     *      tags={"Заявитель юр лицо"},
     *      summary="Обновление данных  заявителя",
     *      description="Обновление данных  заявителя",
     *          @OA\RequestBody(
     *              required=true,
     *              @OA\JsonContent(ref="#/components/schemas/ApplicantLERequest")
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
     *              @OA\JsonContent(ref="#/components/schemas/ALEResource")
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
    public function update(ApplicantLERequest $request, $id)
    {
        $data = $request->validated();
        $apllicant = ApplicantLegalEntity::findOrfail($id);
        $apllicant->update($data);
        return (new ItemALEResource($apllicant))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     *      path="api/applicant-legal-entity/{id}",
     *      tags={"Адреса и дополнительные данные"},
     *      summary="Добавление адреса и дополнительных данных для юр лица",
     *      description="Добавление адреса и дополнительных данных для юр лица",
     *          @OA\RequestBody(
     *              required=true,
     *              @OA\JsonContent(ref="#/components/schemas/ApplicantLEAddressRequest")
     *           ),
     *          @OA\Parameter(
     *              name="id",
     *              description="id заявителя юр лица",
     *              required=true,
     *              in="path",
     *              @OA\Schema(
     *                  type="integer"
     *              )
     *          ),
     *          @OA\Response(
     *              response=200,
     *              description="Successful operation",
     *              @OA\JsonContent(ref="#/components/schemas/ALEAddressResource")
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
    public function updateAddress(ApplicantLEAddressRequest $request, $id)
    {
        $data = $request->validated();
        $apllicant = ApplicantLegalEntity::findOrfail($id);
        $apllicant->registration_address = $request->registration_address;
        $apllicant->postal_address = $request->postal_address;
        $apllicant->soate_registration_address = $request->soate_registration_address;
        $apllicant->soate_postal_address = $request->soate_postal_address;
        $apllicant->last_name_manager = $request->last_name_manager;
        $apllicant->name_manager = $request->name_manager;
        $apllicant->patronymic_manager = $request->patronymic_manager;
        $apllicant->position_manager = $request->position_manager;
        $apllicant->save();

        return (new ItemALEAddressResource($apllicant))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * @OA\Delete(
     *      path="api/applicant-legal-entity/{id}",
     *      tags={"Заявитель юр лицо"},
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
        $apllicant = ApplicantLegalEntity::findOrfail($id);
        $apllicant->delete();
        return response()->json(null, 204);
    }
}
