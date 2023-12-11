<?php

namespace App\Http\Controllers;

use App\Http\Requests\Document\DocumentStoreFromArrayRequest;
use App\Http\Requests\Document\DocumentStoreRequest;
use App\Http\Requests\Document\DocumentUpdateRequest;
use App\Http\Resources\Document\DocumentShowResource;
use App\Models\Appeal;
use App\Models\AppealAnswer;
use App\Models\ApplicantIndividual;
use App\Models\ApplicantLegalEntity;
use App\Models\AuditRegistry;
use App\Models\CaseDeal;
use App\Models\Document;
use App\Models\File;
use App\Services\NextCloudService;

class DocumentController extends Controller
{
    /**
     * @OA\Post(
     *      path="/api/documents-from-array",
     *      tags={"Документы"},
     *      summary="Создание документа удостоверяющего личность из массива",
     *      description="Создание документа удостоверяющего личность из массива",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/DocumentStoreFromArrayRequest")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/DocumentShowResource")
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=419,
     *          description="CSRF token mismatch"
     *      )
     *     )
     */
    public function storeArray(DocumentStoreFromArrayRequest $request)
    {
        $data = $request->validated();
        $documentRequests = $data['documents'];
        $firstElement = reset($documentRequests);
        if(!empty($firstElement['applicant_individual_id'])) {
            $owner = ApplicantIndividual::find($firstElement['applicant_individual_id']);
        } elseif (!empty($firstElement['appeal_id'])) {
            $owner = Appeal::find($firstElement['appeal_id']);
        } elseif (!empty($firstElement['appeal_answer_id'])) {
            $owner = AppealAnswer::find($firstElement['appeal_answer_id']);
        } elseif (!empty($firstElement['case_id'])) {
            $owner = CaseDeal::find($firstElement['case_id']);
        } elseif (!empty($firstElement['audit_registry_id'])) {
            $owner = AuditRegistry::find($firstElement['audit_registry_id']);
        } else {
            $owner = ApplicantLegalEntity::find($firstElement['applicant_legal_entity_id']);
        }

        $existsDocuments = [];
        $newDocuments = [];
        foreach ($documentRequests as $documentRequest) {
            if (isset($documentRequest['id'])) {
                $existsDocuments[] = $documentRequest['id'];
            } else {
                $service = new NextCloudService();
                $directory = $owner->getDirectory();
                $fileName = time() . '_' . rand(10, 99) . '.' . $documentRequest['file']->getClientOriginalExtension();
                $documentRequest['file'] = $service->saveFile($documentRequest['file'], $directory ,$fileName);
                $documentRequest['link'] = $owner->shareDocument($documentRequest['file']);
                if (!empty($firstElement['appeal_id']) || !empty($firstElement['appeal_answer_id']) || !empty($firstElement['case_id']) || !empty($firstElement['audit_registry_id'])) {
                    $document = File::create($documentRequest);
                } else {
                    $document = Document::create($documentRequest);
                }
                $newDocuments[] = $document->id;
            }
        }
        foreach ($owner->documents as $document) {
            if (!in_array($document->id, $existsDocuments) && !in_array($document->id, $newDocuments)) {
                $document->delete();
            }
        }
        $owner = $owner->fresh();

        return DocumentShowResource::collection($owner->documents ?? []);
    }

     /**
     * @OA\Post(
     *      path="/api/documents",
     *      tags={"Документы"},
     *      summary="Создание документа удостоверяющего личность",
     *      description="Создание документа удостоверяющего личность",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/DocumentStoreRequest")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/DocumentShowResource")
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=419,
     *          description="CSRF token mismatch"
     *      )
     *     )
     */
    public function store(DocumentStoreRequest $request)
    {
        $data = $request->validated();

        if(isset($data['applicant_individual_id'])) {
            $owner = ApplicantIndividual::find($data['applicant_individual_id']);
        } else {
            $owner = ApplicantLegalEntity::find($data['applicant_legal_entity_id']);
        }

        $service = new NextCloudService();
        $directory = $owner->getDirectory();
        $fileName = time() . '_' . rand(10, 99) . '.' . $request['file']->getClientOriginalExtension();
        $data['file'] = $service->saveFile($data['file'], $directory ,$fileName);
        $data['link'] = $owner->shareDocument($data['file']);

        $document = Document::create($data);
        $owner->documents()->save($document);

        return new DocumentShowResource($document);
    }

    /**
     * @OA\Get(
     *      path="/api/documents/{id}",
     *      tags={"Документы"},
     *      summary="Получение документа удостоверяющего личность",
     *      description="Получение документа удостоверяющего личность",
     *      @OA\Parameter(
     *          name="id",
     *          description="ID документа",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/DocumentShowResource")
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=419,
     *          description="CSRF token mismatch"
     *      )
     *     )
     */
    public function show($id)
    {
        $document = Document::findOrFail($id);

        return new DocumentShowResource($document);
    }

    /**
     * @OA\Put(
     *      path="/api/documents/{id}",
     *      tags={"Документы"},
     *      summary="Обновление документа",
     *      description="Обновление документа",
     *      @OA\Parameter(
     *          name="id",
     *          description="ID документа",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/DocumentStoreRequest")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/DocumentShowResource")
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=419,
     *          description="CSRF token mismatch"
     *      )
     *     )
     */
    public function update(DocumentUpdateRequest $request, $id)
    {
        $data = $request->validated();
        $document = Document::find($id);
        if ($request->hasFile('file')) {
            $service = new NextCloudService();
            $service->deleteFile($document->file);
            $directory = $document->owner->getDirectory();
            $fileName = time() . '_' . rand(10, 99) . '.' . $request['file']->getClientOriginalExtension();
            $data['file'] = $service->saveFile($data['file'], $directory ,$fileName);
        }

        $document->update($data);

        return response()->json(['message' => 'Document updated successfully'], 200);
    }

    /**
     * @OA\Delete(
     *      path="/api/documents/{id}",
     *      tags={"Документы"},
     *      summary="Удаление документа удостоверяющего личность",
     *      description="Удаление документа удостоверяющего личность",
     *      @OA\Parameter(
     *          name="id",
     *          description="ID документа",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=419,
     *          description="CSRF token mismatch"
     *      )
     *     )
     */
    public function destroy($id)
    {
        $document = Document::find($id);
        $service = new NextCloudService();
        $service->deleteFile($document->file);
        $document->delete();

        return response()->json(['message' => 'Document deleted successfully'], 200);
    }

    /**
     * @OA\Get(
     *      path="/api/documents-legal/{id}",
     *      tags={"Документы"},
     *      summary="Получение документов юр. лица",
     *      description="Получение документов юр. лица",
     *          @OA\Response(
     *              response=200,
     *              description="Successful operation",
     *              @OA\JsonContent(ref="#/components/schemas/DocumentShowResource")
     *          ),
     *          @OA\Parameter(
     *              name="id",
     *              description="id юридического лица",
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
    public function getLegalDocuments($id)
    {
        $legalEntity = ApplicantLegalEntity::findOrFail($id);

        return DocumentShowResource::collection($legalEntity->documents);
    }

    /**
     * @OA\Get(
     *      path="/api/documents-individual/{id}",
     *      tags={"Документы"},
     *      summary="Получение документов физ. лица",
     *      description="Получение документов физ. лица",
     *          @OA\Response(
     *              response=200,
     *              description="Successful operation",
     *              @OA\JsonContent(ref="#/components/schemas/DocumentShowResource")
     *          ),
     *          @OA\Parameter(
     *              name="id",
     *              description="id юридического лица",
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
    public function getIndividualDocuments($id)
    {
        $applicantIndividual = ApplicantIndividual::findOrFail($id);

        return DocumentShowResource::collection($applicantIndividual->documents);
    }

    public function docxToPdf(Request $request)
    {


    }
}
