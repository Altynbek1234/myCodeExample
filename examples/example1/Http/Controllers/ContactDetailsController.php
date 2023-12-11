<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactDetailsReqauest;
use App\Http\Resources\ContactDetailsResource;
use App\Models\ContactDetails;
use App\Models\ContactPerson;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;

class ContactDetailsController extends Controller
{
    /**
     * @OA\Get(
     *      path="api/contact-detail",
     *      tags={"Контактные данные"},
     *      summary="Список контактныx данныx",
     *      description="Получение контактныx данныx",
     *          @OA\Response(
     *              response="200",
     *              description="Returns the  collection",
     *              @OA\JsonContent(
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/ContactDetailsResource")
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
        $contactData = ContactDetails::paginate(10);
        return (ContactDetailsResource::collection($contactData))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     *      path="/api/contact-detail",
     *      tags={"Контактные данные"},
     *      summary="Создание контактных данных",
     *      description="Создание контактных данных",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/ContactDetailsRequest")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/ContactDetailsResource")
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
    public function store(ContactDetailsReqauest $request)
    {
        $data = $request->validated();
        $contactData = ContactDetails::create($data);

        return (new ContactDetailsResource($contactData))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * @OA\Get(
     *      path="api/contact-detail/{id}",
     *      tags={"Контактные данные"},
     *      summary="Получение контактных данныx",
     *      description="Получение контактных данныx",
     *      @OA\Parameter(
     *              name="id",
     *              description="id данных",
     *              required=true,
     *              in="path",
     *              @OA\Schema(
     *                  type="integer"
     *              )
     *          ),
     *          @OA\Response(
     *              response=200,
     *              description="Successful operation",
     *              @OA\JsonContent(ref="#/components/schemas/ContactDetailsResource")
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
        $contactData = ContactDetails::findOrfail($id);
        return new ContactDetailsResource($contactData);
    }

    /**
     * @OA\Put(
     *      path="api/contact-detail/{id}",
     *      tags={"Контактные данные"},
     *      summary="Обновление контактных данных",
     *      description="Обновление контактных данных",
     *          @OA\RequestBody(
     *              required=true,
     *              @OA\JsonContent(ref="#/components/schemas/ContactDetailsRequest")
     *           ),
     *          @OA\Parameter(
     *              name="id",
     *              description="id данных",
     *              required=true,
     *              in="path",
     *              @OA\Schema(
     *                  type="integer"
     *              )
     *          ),
     *          @OA\Response(
     *              response=200,
     *              description="Successful operation",
     *              @OA\JsonContent(ref="#/components/schemas/ContactDetailsResource")
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
    public function update(ContactDetailsReqauest $request, $id)
    {
        $data = $request->validated();
        $contactData = ContactDetails::findOrfail($id);
        $contactData->update($data);
        return (new ContactDetailsResource($contactData))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * @OA\Delete(
     *      path="api/contact-detai/{id}",
     *      tags={"Контактные данные"},
     *      summary="Удаление контактных данных",
     *      description="Удаление контактных данных",
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
        $contactData = ContactDetails::findOrfail($id);
        $contactData->delete();
        return response()->json(null, 204);
    }

    public function addContactData($contactData, $personContactId)
    {
        $existsContacts = [];
        $contactPersons = ContactPerson::findOrfail($personContactId);
        foreach ($contactData as $data) {
            if ($data['id'] && $data['id'] != null) {
                $contactDataSingle = ContactDetails::findOrfail($data['id']);
                $contactDataSingle->contact_people_id = $personContactId;
                $contactDataSingle->type_id = $data['type_id'];
                $contactDataSingle->value = $data['value'];
                $contactDataSingle->preferred = $data['preferred'];
                $contactDataSingle->note = $data['note'];
                $contactDataSingle->update($contactDataSingle);
                $existsContacts[] = $contactDataSingle->id;
            } else {
                $contactDataSingle = new ContactDetails();
                $contactDataSingle->contact_people_id = $personContactId;
                $contactDataSingle->type_id = $data['type_id'];
                $contactDataSingle->value = $data['value'];
                $contactDataSingle->preferred = $data['preferred'];
                $contactDataSingle->note = $data['note'];
                $contactDataSingle->create($contactDataSingle);
            }
        }
        $allDataDetails = $contactPersons->getDatadetails();

        foreach ($allDataDetails as $data) {
            if(!in_array($data->id, $existsContacts)){
                $data->contactPersons()->delete();
            }
        }
    }
}
