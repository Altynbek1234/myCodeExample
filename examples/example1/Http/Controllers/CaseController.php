<?php

namespace App\Http\Controllers;

use App\Http\Requests\Appeal\AppealStructureCategoriesRequest;
use App\Http\Requests\Appeal\AttachedAppealsRequest;
use App\Http\Requests\Case\CaseActionsRequest;
use App\Http\Requests\Case\CaseDateRequest;
use App\Http\Requests\Case\CaseDoerRequest;
use App\Http\Requests\Case\CaseStructureCategoriesRequest;
use App\Http\Requests\Case\CaseUpdateFromActionRequest;
use App\Http\Resources\Appeal\AppealListResource;
use App\Http\Resources\Appeal\AppealShowResource;
use App\Http\Resources\Case\CaseActionResource;
use App\Http\Resources\Case\CaseListResource;
use App\Http\Resources\References\ActionsOfAkyykatchyResource;
use App\Models\Appeal;
use App\Models\AppealAnswer;
use App\Models\CaseAction;
use App\Models\CaseDate;
use App\Models\CaseDeal;
use App\Models\CaseDoer;
use App\Models\CategoriesOfDepartmentRequests;
use App\Models\ProfilePersonData;
use App\Models\Stage;
use App\Models\StageAction;
use App\Models\StageHistory;
use App\Models\User;
use App\Services\NextCloudService;
use Illuminate\Http\Request;
use App\Http\Requests\Case\CaseStoreRequest;
use App\Http\Resources\Case\CaseShowResource;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;




class CaseController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/case",
     *     tags={"Дела"},
     *     summary="Получение списка дел,
     *     description="Получение списка дел",
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
     *                     ref="#/components/schemas/CaseListResource"
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
        $registered = $request->registered;
        $kindOfCase = $request->input('kind_of_case_id');
        $typeOfSolution = $request->input('types_of_solution_id');
        $person = $request->input('persons');
        $orderBy = $request->input('order_by', 'start_date');
        $orderDir = $request->input('order_dir', 'desc');
        $executor = $request->input('executor');
        $structureCategory = $request->input('structure_category');
        $stage_id = $request->input('stage_id');
        $query = CaseDeal::query()->orderBy($orderBy, $orderDir);

        if ($registered) {
            $query->whereNotNull('number')->get();
        } else {
            $query->orderBy('id', 'DESC')->get();
        }

        if ($kindOfCase) {
            $query->whereHas('kindOfCase', function ($query) use ($kindOfCase) {
                $query->where('kind_of_case_id', $kindOfCase);
            });
        }

        if ($typeOfSolution) {
            $query->whereHas('typesOfSolution', function ($query) use ($typeOfSolution) {
                $query->where('types_of_solution_id', $typeOfSolution);
            });
        }

        if($person) {
            $query->whereHas('persons', function ($query) use ($person) {
                $query->where('applicant_individuals.id', $person);
            });
        }

        if ($structureCategory) {
            $query->whereJsonContains('structure_categories', [
                'categories_of_department_request' => ['id' => $structureCategory]
            ] );
        }

        if ($executor) {
            $query->whereHas('caseDoers', function ($query) use ($executor) {
                $query->where('doers', 'like', '%"user_id":"' . $executor . '"%');
            });
        }

        if ($stage_id) {
            $query->whereHas('stage', function ($query) use ($stage_id) {
                $query->where('stage_id', $stage_id);
            });
        }

        $cases = $query->paginate(10);

        return CaseListResource::collection($cases);
    }

    /**
     * @OA\Post(
     *     path="/api/case",
     *     tags={"Данные Дела"},
     *     summary="Создание Дела",
     *     description="Создание Дела",
     *     operationId="case.store",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CaseStoreRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(ref="#/components/schemas/CaseShowResource")
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

    public function store(CaseStoreRequest $request)
    {
        $data = $request->validated();
        $case = new CaseDeal();
        $case->stage_id = StageHistory::START_STAGE_CASE_ID;

        if (!empty($data['appeal_id'])) {
            $case->appeal_id = $data['appeal_id'];
            $case->kind_of_case_id = 1;
            $case->stage_id = 26;
        }

        if (!empty($data['kind_of_case_id'])) {
            $case->kind_of_case_id = $data['kind_of_case_id'];
        }

        $case->representative_io_id = Auth::user()->organizationEmployee->department->representativeIo->id;

        if (!empty($data['summary'])) {
            $case->summary = $data['summary'];
        }

        if (!empty($data['start_date'])) {
            $case->start_date = $data['start_date'];
        }

        if (!empty($data['number'])) {
            $case->number = $data['number'];
        }

        if (!empty($data['status_stated_fact_id'])) {
            $case->status_stated_fact_id = $data['status_stated_fact_id'];
        }

        if (!empty($data['outcome_result'])) {
            $case->outcome_result = $data['outcome_result'];
        }

        if (!empty($data['types_of_solution_id'])) {
            $case->types_of_solution_id = $data['types_of_solution_id'];
        }

        if (!empty($data['include_in_report'])) {
            $case->include_in_report = $data['include_in_report'];
        }

        if (!empty($data['report_text'])) {
            $case->report_text = $data['report_text'];
        }

        $stageHistory = StageHistory::create([
            'case_deal_id' => $case->id,
            'stage_id' => StageHistory::START_STAGE_CASE_ID,
            'user_id' => auth()->user()->id,
        ]);

        if (!empty($data['appeal_id'])) {
            $stageHistory['stage_id'] = 26;
        }

        $case->save();
        $case->stageHistories()->save($stageHistory);

        $appeal = $case->appeal;
        if ($appeal) {
            return new AppealShowResource($appeal);
        }

        return new CaseShowResource($case);
    }

    /**
     * @OA\Get(
     *      path="api/case/{id}",
     *     tags={"Данные Дела"},
     *     summary="Получение Дела",
     *      description="Получение Дела",
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
     *         @OA\JsonContent(ref="#/components/schemas/CaseShowResource")
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
        $case = CaseDeal::findOrfail($id);
        return new CaseShowResource($case);
    }

    /**
     * @OA\POST (
     *      path="api/case/{id}",
     *      tags={"Данные Дела"},
     *      summary="Обновление Дела",
     *      description="Обновление Дела",
     *          @OA\RequestBody(
     *              required=true,
     *              @OA\JsonContent(ref="#/components/schemas/CaseStoreRequest")
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
     *              @OA\JsonContent(ref="#/components/schemas/CaseShowResource")
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

    public function update(CaseStoreRequest $request, $id)
    {

        $data = $request->validated();

        $case = CaseDeal::findOrfail($id);


        if (!empty($data['appeal_id'])) {
            $case->appeal_id = $data['appeal_id'];
        }

        if (!empty($data['kind_of_case_id'])) {
            $case->kind_of_case_id = $data['kind_of_case_id'];
        }

        if (!empty($data['summary'])) {
            $case->summary = $data['summary'];
        }

        if (!empty($data['start_date'])) {
            $case->start_date = $data['start_date'];
        }

        if (!empty($data['status_stated_fact_id'])) {
            $case->status_stated_fact_id = $data['status_stated_fact_id'];
        }

        if (!empty($data['outcome_result'])) {
            $case->outcome_result = $data['outcome_result'];
        }

        if (!empty($data['types_of_solution_id'])) {
            $case->types_of_solution_id = $data['types_of_solution_id'];
        }

        if (!empty($data['report_text'])) {
            $case->report_text = $data['report_text'];
        }

        if (!empty($data['include_in_report']) && $data['include_in_report'] === true) {
            $case->include_in_report = true;
        } else {
            $case->include_in_report = false;
            $case->report_text = null;
        }

        $case->update();

        if (!empty($data['persons'])) {
            $personsData = $data['persons'];

            $syncData = [];
            foreach ($personsData as $personData) {
                $profile = $personData['profile'];
                if (!empty($profile['id'])) {
                    $profilePersonData = ProfilePersonData::findOrFail($profile['id']);
                } else {
                    $profilePersonData = new ProfilePersonData();
                }
                $profilePersonData->a_individual_id = $personData['id'];
                $profilePersonData->case_id = $case->id;
                if (!empty($profile['age']) && is_int($profile['age']) && $profile['age'] != 80001 && $profile['age'] != 80000) {
                    $profilePersonData->age = $profile['age'];
                } else if (!empty($profile['age'])) {
                    $profilePersonData->age = $profile['age'];
                }
                if (!empty($profile['nationality']) && !is_numeric($profile['nationality'])) {
                    $profilePersonData->nationality_id = 80002;
                    $profilePersonData->other_nationality = $profile['nationality'];
                } else if (!empty($profile['nationality'])) {
                    $profilePersonData->nationality_id = $profile['nationality'];
                }

                if (!empty($profile['social_status']) && !is_numeric($profile['social_status'])) {
                    $profilePersonData->social_status_id = 80002;
                    $profilePersonData->other_social_status = $profile['social_status'];
                } else if (!empty($profile['social_status'])) {
                    $profilePersonData->social_status_id = $profile['social_status'];
                }

                if (!empty($profile['family_status']) && !is_numeric($profile['family_status'])) {
                    $profilePersonData->family_status_id = 80002;
                    $profilePersonData->other_family_status = $profile['family_status'];
                } else if (!empty($profile['family_status'])) {
                    $profilePersonData->family_status_id = $profile['family_status'];
                }

                if (!empty($profile['level_of_education']) && !is_numeric($profile['level_of_education'])) {
                    $profilePersonData->level_of_education_id = 80002;
                    $profilePersonData->other_level_of_education = $profile['level_of_education'];
                } else if (!empty($profile['level_of_education'])) {
                    $profilePersonData->level_of_education_id = $profile['level_of_education'];
                }

                if (!empty($profile['income_levels']) && !is_numeric($profile['income_levels'])) {
                    $profilePersonData->income_levels_id = 80002;
                    $profilePersonData->other_income_levels = $profile['income_levels'];
                } else if (!empty($profile['income_levels'])) {
                    $profilePersonData->income_levels_id = $profile['income_levels'];
                }

                if (!empty($profile['migration_status']) && !is_numeric($profile['migration_status'])) {
                    $profilePersonData->migration_status_id = 80002;
                    $profilePersonData->other_migration_status = $profile['migration_status'];
                } else if (!empty($profile['migration_status'])) {
                    $profilePersonData->migration_status_id = $profile['migration_status'];
                }

                if (!empty($profile['purpose_of_migrant']) && !is_numeric($profile['purpose_of_migrant'])) {
                    $profilePersonData->purpose_of_migrant_id = 80002;
                    $profilePersonData->other_purpose_of_migrant = $profile['purpose_of_migrant'];
                } else if (!empty($profile['purpose_of_migrant'])) {
                    $profilePersonData->purpose_of_migrant_id = $profile['purpose_of_migrant'];
                }

                if (!empty($profile['getting_disabilities']) && !is_numeric($profile['getting_disabilities'])) {
                    $profilePersonData->getting_disabilities_id = 80002;
                    $profilePersonData->other_getting_disabilities = $profile['getting_disabilities'];
                } else if (!empty($profile['getting_disabilities'])) {
                    $profilePersonData->getting_disabilities_id = $profile['getting_disabilities'];
                }

                if (!empty($profile['limited_health_id']) && !is_numeric($profile['limited_health_id'])) {
                    $profilePersonData->limited_health_id = 80002;
                    $profilePersonData->other_limited_healths = $profile['limited_health_id'];
                } else if (!empty($profile['limited_health_id'])) {
                    $profilePersonData->limited_health_id = $profile['limited_health_id'];
                }

                if (!empty($profile['period_of_disability'])) {
                    if ($profile['period_of_disability'] == 1) {
                        $profilePersonData->period_of_disability = 'temporary';
                    } else {
                        $profilePersonData->period_of_disability = 'permanent';
                    }
                }

                if (!empty($profile['sickness']) && !is_numeric($profile['sickness'])) {
                    $profilePersonData->sickness_id = 80002;
                    $profilePersonData->other_sickness = $profile['sickness'];
                } else if (!empty($profile['sickness'])) {
                    $profilePersonData->sickness_id = $profile['sickness'];
                }
                if (!empty($profile['registered_psychiatric'])) {
                    $registered_psychiatric = $profile['registered_psychiatric'];
                    $date = \DateTime::createFromFormat('Y-m-d', $registered_psychiatric);

                    if ($date && $date->format('Y-m-d') == $registered_psychiatric) {
                        $profilePersonData->registered_psychiatric = 1;
                        $profilePersonData->date_registered_psychiatric = $profile['registered_psychiatric'];
                    } else {
                        if (!is_numeric($profile['registered_psychiatric'])) {
                            $profilePersonData->registered_psychiatric = 80002;
                            $profilePersonData->date_registered_psychiatric = null;
                            $profilePersonData->other_registered_psychiatric = $profile['registered_psychiatric'];
                        }
                        if ($profile['registered_psychiatric'] == 80000) {
                            $profilePersonData->date_registered_psychiatric = null;
                            $profilePersonData->other_registered_psychiatric = null;
                            $profilePersonData->registered_psychiatric = $profile['registered_psychiatric'];
                        }
                        if ($profile['registered_psychiatric'] == 80001) {
                            $profilePersonData->date_registered_psychiatric = null;
                            $profilePersonData->other_registered_psychiatric = null;
                            $profilePersonData->registered_psychiatric = $profile['registered_psychiatric'];
                        }
                    }
                }
                if (!empty($profile['criminal_record']) && !is_numeric($profile['criminal_record'])) {
                    $profilePersonData->criminal_record = 80002;
                    $profilePersonData->other_criminal_record = $profile['criminal_record'];
                } else if (!empty($profile['criminal_record'])) {
                    $profilePersonData->criminal_record = $profile['criminal_record'];
                }

                if (!empty($profile['vulnerable_group']) && !is_numeric($profile['vulnerable_group'])) {
                    $profilePersonData->vulnerable_group_id = 80002;
                    $profilePersonData->other_vulnerable_group = $profile['vulnerable_group'];
                } else if (!empty($profile['vulnerable_group'])) {
                    $profilePersonData->vulnerable_group_id = $profile['vulnerable_group'];
                }

                if (!empty($profile['group_membership']) && !is_numeric($profile['group_membership'])) {
                    $profilePersonData->group_membership_id = 80002;
                    $profilePersonData->other_group_memberships = $profile['group_membership'];
                } else if (!empty($profile['group_membership'])) {
                    $profilePersonData->group_membership_id = $profile['group_membership'];
                }

                if (!empty($profile['note']) && $profile['note']) {
                    $profilePersonData->note = $profile['note'];
                }

                if (!empty($profile['soate_registration_address']) && $profile['soate_registration_address']) {
                    $profilePersonData->soate_registration_address  = $profile['soate_registration_address'];
                }

                if (!empty($profile['registration_address']) && $profile['registration_address']) {
                    $profilePersonData->registration_address  = $profile['registration_address'];
                }

                if (!empty($profile['soate_postal_address']) && $profile['soate_postal_address']) {
                    $profilePersonData->soate_postal_address  = $profile['soate_postal_address'];
                }

                if (!empty($profile['postal_address']) && $profile['postal_address']) {
                    $profilePersonData->postal_address  = $profile['postal_address'];
                }

                if (!empty($profile['place_work_study']) && $profile['place_work_study']) {
                    $profilePersonData->place_work_study  = $profile['place_work_study'];
                }

                if (!empty($profile['position']) && $profile['position']) {
                    $profilePersonData->position  = $profile['position'];
                }

                $profilePersonData->save();

                $syncData[$personData['id']] = [
                    'profile_id' => $profilePersonData->id
                ];
            }

            $case->persons()->sync($syncData);
        }
        if (!empty($data['organizations'])) {
            $organizations = $data['organizations'];
            foreach($organizations as $organization) {
                if (!empty($organization['defendentList'])) {
                    $organizationsIds[$organization['id']] = ['defendent_ids' => json_encode($organization['defendentList'])];
                } else {
                    $organizationsIds[] = $organization['id'];
                }
            }
            $case->organizations()->sync($organizationsIds);
        }
        if (!empty($data['actions'])) {
            $actions = $data['actions'];
            foreach($actions as $action) {
                if (!empty($action['executors'])) {
                    $actionIds[$action['id']] = ['executors' => json_encode($action['executors'])];
                } else {
                    $actionIds[] = $action['id'];
                }
            }
            $case->actionsOfAkyykatchy()->sync($actionIds);
        }
        return new CaseShowResource($case);
    }

    public function updateFromAction(CaseUpdateFromActionRequest $request, $id, $actionId)
    {
        $case = CaseDeal::findOrfail($id);
        $lastStageHistory = $case->getLastStageHistory();
        $action = StageAction::find($actionId);
        $fields = $request->validated();
        $stage = Stage::find($action->next_stage_id);
        $stageHistories = [
            'case_deal_id' => $case->id,
            'action_id' => $action->id,
            'stage_id' => $action->next_stage_id,
            'prev_stage_id' => $case->stage_id,
            'prev_stage_history_id' => !empty($lastStageHistory) ? $lastStageHistory->id : 23,
            'user_id' => auth()->user()->id,
        ];
        $cases = [
            'stage_id' => $action->next_stage_id,
        ];

        $casesHistory = [];

        foreach ($fields as $key => $value) {
            [$tableName, $fieldName] = explode('-', $key);
            ${$tableName}[$fieldName] = $value;
            $casesHistory['cases'][$fieldName] = $case->{$fieldName};
        }
        $stageHistories['fields_history'] = json_encode($casesHistory);
        StageHistory::create($stageHistories);
        $case->update($cases);

        $appeal = $case->appeal;
        if ($appeal) {
            return new AppealShowResource($appeal);
        }

        return new CaseShowResource($case);
    }


    public function dawngradeFromAction($id, $actionId)
    {
        $case = CaseDeal::findOrfail($id);
        $action = StageAction::find($actionId);
        $lastStageHistory = $case->getLastStageHistory();
        $stageHistoryForRevert = StageHistory::find($lastStageHistory->prev_stage_history_id);
        $newStageHistory = $stageHistoryForRevert->replicate();
        $newStageHistory->action_id = $action->id;
        $newStageHistory->user_id = auth()->user()->id;
        $newStageHistory->save();

        $case->stage_id = $stageHistoryForRevert->stage_id;
        $case->save();
        $fieldsHistory = json_decode($lastStageHistory->fields_history, true);
        if (!empty($fieldsHistory['cases'])) {
            $cases = $fieldsHistory['cases'];
            $case->update($cases);
        }

        $appeal = $case->appeal;
        if ($appeal) {
            return new AppealShowResource($appeal);
        }

        return new CaseShowResource($case);
    }

    public function setCaseStructureCategories(CaseStructureCategoriesRequest $request, $id)
    {
        $data = $request->validated();
        $case = CaseDeal::findOrfail($id);
        $case->structure_categories = json_encode($data['categories']);
        $case->save();

        return $data['categories'];
    }

    public function setAttachedAppeals(AttachedAppealsRequest $request, $id)
    {
        $data = $request->validated();
        $case = CaseDeal::findOrfail($id);
        $case->attached_appeals = json_encode($data['attached_appeals']);
        $case->save();

        $appeals = Appeal::whereIn('id', $data['attached_appeals'])->get();

        return AppealListResource::collection($appeals);
    }

    public function setActions(CaseActionsRequest $request, $id)
    {
        $data = $request->validated();
        $case = CaseDeal::findOrFail($id);
        $actionId = $data['action_id'];
        $caseAction = CaseAction::query()->where('action_id', $actionId)->where('case_id', $id)->first();
        if (!$caseAction) {
            $caseAction = new CaseAction();
            $caseAction->case_id = $id;
            $caseAction->action_id = $actionId;
        }
        $caseAction->date = $data['date'];
        $caseAction->description = $data['description'];
        $caseAction->place = $data['place'];
//        $caseAction->appeal_answer_id = $data['appeal_answer_id'];
        $caseAction->executors = json_encode($data['executors']);
        $caseAction->save();
        $caseActions = $case->caseActions()->get();

        return CaseActionResource::collection($caseActions);
    }

    public function deleteCaseAction($id) {
        $caseAction = CaseAction::findOrFail($id);
        $case = $caseAction->case;
        $caseAction->delete();

        $actions = $case->caseActions()->get();

        return CaseActionResource::collection($actions);
    }

    public function createCaseDate(CaseDateRequest $request, $id)
    {
        $data = $request->validated();
        $case = CaseDeal::findOrfail($id);
        $caseDate = CaseDate::create([
            'case_id' => $case->id,
            'date' => $data['date'],
            'user_id' => auth()->user()->id,
        ]);

        return $caseDate;
    }

    public function createCaseDoer(CaseDoerRequest $request, $id)
    {
        $data = $request->validated();
        $case = CaseDeal::findOrfail($id);
        $caseDoer = CaseDoer::create([
            'case_id' => $case->id,
            'doers' => json_encode($data['doers']),
            'user_id' => auth()->user()->id,
        ]);
        $caseDoer->save();

        $doers = json_decode($caseDoer->doers, true) ?? [];
        foreach ($doers as $key => $doer) {
            $doers[$key]['user'] = User::find($doer['user_id'])->load('organizationEmployee');
        }

        return json_encode($doers);
    }
}
