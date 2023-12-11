<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactPersonRequest;
use App\Http\Resources\ContactPersonIResource;
use App\Models\ApplicantIndividual;
use App\Models\ApplicantLegalEntity;
use App\Models\ContactDetails;
use App\Models\ContactPerson;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\ContactPersonArrayRequest;

class ContactPersonController extends Controller
{
    /**
     * @OA\Post(
     *      path="/contact-person/legal-entity/{id}",
     *      tags={"Контактные лица"},
     *      summary="Добавление контактных лиц для юр. лица",
     *      description="Добавление контактных лиц для юр. лица. В роуте передается id юр. лица.",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/ContactPersonArrayRequest")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/ContactPersonArrayResource")
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
    public function addContactsToLegalEntity(ContactPersonArrayRequest $request, $id)
    {
        $data = $request->validated();
        $contacts = $data['contacts'];
        $existsContacts = [];
        $isNew = [];
        foreach ($contacts as $contact) {
            $contact['applicant_le_id'] = $id;
            if (isset($contact['id'])) {
                $contactPerson = ContactPerson::findOrfail($contact['id']);
                $contactPerson->update($contact);
                $dataDetails = new ContactDetails();
                $dataDetails->addContactData($contact['contact_details'], $contactPerson->id);
                $existsContacts[] = $contactPerson->id;
            } else {
                $contactPerson = ContactPerson::create($contact);
                $isNew[] = $contactPerson->id;
                $dataDetails = new ContactDetails();
                $dataDetails->addContactData($contact['contact_details'], $contactPerson->id);
            }
        }
        $legalEntity = ApplicantLegalEntity::findOrfail($id);
        $legalEntityContacts = $legalEntity->contactPersons;
        foreach ($legalEntityContacts as $applicantIndividualContact) {
            if (!in_array($applicantIndividualContact->id, $existsContacts) && !in_array($applicantIndividualContact->id, $isNew)) {
                $applicantIndividualContact->delete();
            }
        }
        $legalEntity = ApplicantLegalEntity::findOrfail($id);
        return (ContactPersonIResource::collection($legalEntity->contactPersons))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * @OA\Post(
     *      path="api/contact-person/individual/{id}",
     *      tags={"Контактные лица"},
     *      summary="Добавление контактных лиц для физического лица",
     *      description="Добавление контактных лиц для физического лица. В роуте id - id физического лица",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/ContactPersonArrayRequest")
     *      ),
     *     @OA\Parameter(
     *              name="id",
     *              description="id физ лица",
     *              required=true,
     *              in="path",
     *              @OA\Schema(
     *                  type="integer"
     *              )
     *          ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/ContactPersonArrayResource")
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
    public function addContactsToIndividual(ContactPersonArrayRequest $request, $id)
    {
        $data = $request->validated();
        $contacts = $data['contacts'];

        $existsContacts = [];
        $isNew = [];
        foreach ($contacts as $contact) {
            $contact['applicant_indiv_id'] = $id;
            if (isset($contact['id'])) {
                $contactPerson = ContactPerson::findOrfail($contact['id']);
                $contactPerson->update($contact);
                $dataDetails = new ContactDetails();
                $dataDetails->addContactData($contact['contact_details'], $contactPerson->id);
                $existsContacts[] = $contactPerson->id;
            } else {
                $contactPerson = ContactPerson::create($contact);
                $isNew[] = $contactPerson->id;
                $dataDetails = new ContactDetails();
                $dataDetails->addContactData($contact['contact_details'], $contactPerson->id);
            }
        }
        $applicantIndividual = ApplicantIndividual::findOrfail($id);
        $applicantIndividualContacts = $applicantIndividual->contactPersons;
        foreach ($applicantIndividualContacts as $applicantIndividualContact) {
            if (!in_array($applicantIndividualContact->id, $existsContacts) && !in_array($applicantIndividualContact->id, $isNew)) {
                $applicantIndividualContact->delete();
            }
        }
        $applicantIndividual = ApplicantIndividual::findOrfail($id);
        return (ContactPersonIResource::collection($applicantIndividual->contactPersons))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * @OA\Get(
     *      path="api/contact-person/legal-entity/{id}",
     *      tags={"Контактные лица"},
     *      summary="Получение контактного лица по юр. лицу",
     *      description="Получение контактного лица по юр. лицу",
     *          @OA\Response(
     *              response=200,
     *              description="Successful operation",
     *              @OA\JsonContent(ref="#/components/schemas/ContactPersonArrayResource")
     *          ),
     *          @OA\Parameter(
     *              name="id",
     *              description="id контактного лица",
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
    public function getContactPersonFromLegalEntity($id)
    {
        $legalEntity = ApplicantLegalEntity::findOrfail($id);
        $contactPersons = $legalEntity->contactPersons;

        return (ContactPersonIResource::collection($contactPersons))
            ->response()
            ->setStatusCode(Response::HTTP_OK);

    }

    /**
     * @OA\Get(
     *      path="/contact-person/individual/{id}",
     *      tags={"Контактные лица"},
     *      summary="Получение контактного лица по физ. лицу",
     *      description="Получение контактного лица по физ. лицу",
     *          @OA\Response(
     *              response=200,
     *              description="Successful operation",
     *              @OA\JsonContent(ref="#/components/schemas/ContactPersonArrayResource")
     *          ),
     *          @OA\Parameter(
     *              name="id",
     *              description="id контактного лица",
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
    public function getContactPersonFromApplicantIndividual($id)
    {
        $applicantIndividual = ApplicantIndividual::findOrfail($id);
        $contactPersons = $applicantIndividual->contactPersons;

        return (ContactPersonIResource::collection($contactPersons))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

}
