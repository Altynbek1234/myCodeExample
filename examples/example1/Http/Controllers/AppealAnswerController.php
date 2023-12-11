<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppealAnswer\AppealAnswerUpdateFromActionRequest;
use App\Http\Resources\AppealAnswer\AppealAnswerListResource;
use App\Models\AppealAnswer;
use App\Models\Stage;
use App\Models\StageAction;
use App\Models\StageHistory;
use App\Http\Requests\AppealAnswer\AppealAnswerStoreRequest;
use App\Http\Resources\AppealAnswer\AppealAnswerShowResource;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AppealAnswerController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/appeal-answer",
     *     tags={"Ответы на обращения"},
     *     summary="Получение списка ответов на обращения",
     *     description="Получение списка ответов на обращения",
     *     operationId="index",
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Поисковая фраза",
     *         required=false,
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
     *                 type="array",
     *                 @OA\Items(
     *                     ref="#/components/schemas/AppealAnswerListResource"
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
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
    public function index(Request $request)
    {
        $orderBy = $request->input('order_by', 'sent_date');
        $orderDir = $request->input('order_dir', 'desc');
        $docType = $request->input('doc_type_id');
        $executor = $request->input('executor_id');
        $outgoingSendingChannel = $request->input('outgoing_sending_channel_id');
        $query = AppealAnswer::query()->orderBy($orderBy, $orderDir);

        if ($docType) {
            $query->whereHas('docType', function ($query) use ($docType) {
                $query->where('doc_type_id', $docType);
            });
        }

        if ($executor) {
            $query->where('executor_id', $executor);
        }

        if ($outgoingSendingChannel) {
            $query->whereHas('outgoingSendingChannel', function ($query) use ($outgoingSendingChannel) {
                $query->where('outgoing_sending_channel_id', $outgoingSendingChannel);
            });
        }

        $appealAnswers = $query->paginate(10);
        return AppealAnswerListResource::collection($appealAnswers);
    }

    /**
     * @OA\Post(
     *     path="/api/appeal-answer",
     *     tags={"Ответ на Обращения"},
     *     summary="Создание ответа на обращения",
     *     description="Создание ответа на обращения",
     *     operationId="appeal-answer.store",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/AppealAnswerStoreRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(ref="#/components/schemas/AppealAnswerShowResource")
     *     ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=419,
     *          description="CSRF token mismatch"
     *      )
     * )
     */
    public function store(AppealAnswerStoreRequest $request)
    {
        $data = $request->validated();
        $appealAnswer = new AppealAnswer();
        $appealAnswer->stage_id = StageHistory::START_STAGE_APPEAL_ANSWER_ID;
        $appealAnswer->appeal_id = $data['appeal_id'];
        $appealAnswer->applicant = json_encode($data['applicant']) ?? null;
        $appealAnswer->issued_number = $data['issued_number'];
        $appealAnswer->summary = $data['summary'];
        $appealAnswer->issued_date = $data['issued_date'];
        $appealAnswer->sent_date = $data['sent_date'];
        $appealAnswer->outgoing_sending_channel_id = $data['outgoing_sending_channel_id'];
        $appealAnswer->executor_id = $data['executor_id'];
        $appealAnswer->person = json_encode($data['person']);
        $appealAnswer->organization = json_encode($data['organization']);
        $appealAnswer->doc_type_id = $data['doc_type_id'];
        $appealAnswer->whom_id = $data['whom_id'];
        $appealAnswer->institution_id = $data['institution_id'];
        $appealAnswer->another_addressee = $data['another_addressee'];

        $stageHistory = StageHistory::create([
            'appeal_answer_id' => $appealAnswer->id,
            'stage_id' => StageHistory::START_STAGE_APPEAL_ANSWER_ID,
            'user_id' => auth()->user()->id,
        ]);
        $appealAnswer->save();
        $appealAnswer->stageHistories()->save($stageHistory);
        return (new AppealAnswerShowResource($appealAnswer))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * @OA\Get(
     *      path="api/appeal-answer/{id}/show",
     *     tags={"Ответ на Обращения"},
     *     summary="Создание ответа на обращения",
     *      description="Получение ответов на обращения",
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
     *         @OA\JsonContent(ref="#/components/schemas/AppealAnswerShowResource")
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
    public function show(AppealAnswer $id)
    {
        return new AppealAnswerShowResource($id);
    }

    /**
     * @OA\Put(
     *      path="api/appeal-answer/{id}",
     *      tags={"Ответ на Обращения"},
     *      summary="Обновление Ответов на Обращения",
     *      description="Обновление Ответов на Обращения",
     *          @OA\RequestBody(
     *              required=true,
     *              @OA\JsonContent(ref="#/components/schemas/AppealAnswerStoreRequest")
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
     *              @OA\JsonContent(ref="#/components/schemas/AppealAnswerShowResource")
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

    public function update(AppealAnswerStoreRequest $request, $id)
    {
        $data = $request->validated();

        $appealAnswer = AppealAnswer::findOrfail($id);

        $appealAnswer->appeal_id = $data['appeal_id'];
        $appealAnswer->applicant = json_encode($data['applicant']);
        $appealAnswer->issued_number = $data['issued_number'];
        $appealAnswer->summary = $data['summary'];
        $appealAnswer->issued_date = $data['issued_date'];
        $appealAnswer->sent_date = $data['sent_date'];
        $appealAnswer->outgoing_sending_channel_id = $data['outgoing_sending_channel_id'];
        $appealAnswer->executor_id = $data['executor_id'];
        $appealAnswer->person = json_encode($data['person']);
        $appealAnswer->organization = json_encode($data['organization']);
        $appealAnswer->doc_type_id = $data['doc_type_id'];
        $appealAnswer->whom_id = $data['whom_id'];
        $appealAnswer->institution_id = $data['institution_id'];
        $appealAnswer->another_addressee = $data['another_addressee'];

        $appealAnswer->update();
        return (new AppealAnswerShowResource($appealAnswer))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    public function updateFromAction(AppealAnswerUpdateFromActionRequest $request, $id, $actionId)
    {
        $appealAnswer = AppealAnswer::findOrfail($id);
        $lastStageHistory = $appealAnswer->getLastStageHistory();
        $action = StageAction::find($actionId);
        $fields = $request->validated();
        $stage = Stage::find($action->next_stage_id);
        $stageHistories = [
            'appeal_answer_id' => $appealAnswer->id,
            'action_id' => $action->id,
            'stage_id' => $action->next_stage_id,
            'prev_stage_id' => $appealAnswer->stage_id,
            'prev_stage_history_id' => !empty($lastStageHistory) ? $lastStageHistory->id : 23,
            'user_id' => auth()->user()->id,
        ];
        $appealAnswers = [
            'stage_id' => $action->next_stage_id,
        ];

        $appealAnswersHistory = [];

        foreach ($fields as $key => $value) {
            [$tableName, $fieldName] = explode('-', $key);
            ${$tableName}[$fieldName] = $value;
            $appealAnswersHistory['appealAnswers'][$fieldName] = $appealAnswer->{$fieldName};
        }
        $stageHistories['fields_history'] = json_encode($appealAnswersHistory);
        StageHistory::create($stageHistories);
        $appealAnswer->update($appealAnswers);

        return new AppealAnswerShowResource($appealAnswer);
    }


    public function dawngradeFromAction($id, $actionId)
    {
        $appealAnswer = AppealAnswer::findOrfail($id);
        $action = StageAction::find($actionId);
        $lastStageHistory = $appealAnswer->getLastStageHistory();
        $stageHistoryForRevert = StageHistory::find($lastStageHistory->prev_stage_history_id);
        $newStageHistory = $stageHistoryForRevert->replicate();
        $newStageHistory->action_id = $action->id;
        $newStageHistory->user_id = auth()->user()->id;
        $newStageHistory->save();

        $appealAnswer->stage_id = $stageHistoryForRevert->stage_id;
        $appealAnswer->save();
        $fieldsHistory = json_decode($lastStageHistory->fields_history, true);
        if (!empty($fieldsHistory['appealAnswers'])) {
            $appealAnswers = $fieldsHistory['appealAnswers'];
            $appealAnswer->update($appealAnswers);
        }

        return new AppealAnswerShowResource($appealAnswer);
    }
}
