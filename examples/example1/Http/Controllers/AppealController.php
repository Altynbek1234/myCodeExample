<?php

namespace App\Http\Controllers;

use App\Http\Requests\Appeal\AppealDateRequest;
use App\Http\Requests\Appeal\AppealDoerRequest;
use App\Http\Requests\Appeal\AppealFavoriteRequest;
use App\Http\Requests\Appeal\AppealReadRequest;
use App\Http\Requests\Appeal\AppealStoreRequest;
use App\Http\Requests\Appeal\AppealStructureCategoriesRequest;
use App\Http\Requests\Appeal\AppealUpdateFromActionRequest;
use App\Http\Requests\Appeal\AttachedAppealsRequest;
use App\Http\Requests\Case\CaseActionsRequest;
use App\Http\Requests\Organization\OrganizationArrayRequest;
use App\Http\Resources\Appeal\AppealListResource;
use App\Http\Resources\Appeal\AppealShowResource;
use App\Http\Resources\Case\CaseActionResource;
use App\Http\Resources\OrganizationResources\OrganizationResource;
use App\Models\Appeal;
use App\Models\AppealDate;
use App\Models\AppealDoer;
use App\Models\CaseAction;
use App\Models\Court;
use App\Models\ProfilePersonData;
use App\Models\StageAction;
use App\Models\StageHistory;
use App\Http\Resources\Appeal\AppealDependenciesResource;
use App\Models\User;
use App\Services\Notification\GetAppealDataForWebNotification;
use App\Services\Notification\WebNotification;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use App\Models\Stage;


class AppealController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/appeals",
     *     tags={"Обращения"},
     *     summary="Получение списка обращений",
     *     description="Получение списка обращений",
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
     *                     ref="#/components/schemas/AppealListResource"
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
        $search = $request->search;
        $registered = $request->registered;
        $user = auth()->user();
        $stages = $user->getStagesPermittedForView();
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $characterOfQuestion = $request->input('character_of_question');
        $description = $request->input('description');
        $number = $request->input('number');
        $applicantSearch = $request->input('applicant');
        $personSearch = $request->input('person');
        $type_of_appeal_id = $request->input('type_of_appeal_id');
        $receipt_channel_id = $request->input('receipt_channel_id');
        $representative_io_id = $request->input('representative_io_id');
        $type_appeal_count_id = $request->input('type_appeal_count_id');
        $frequenciesy_of_appeal_id = $request->input('frequenciesy_of_appeal_id');
        $organization_id = $request->input('organization_id');
        $appeal_language_id = $request->input('appeal_language_id');
        $result = $request->input('result');
        $stage_id = $request->input('stage_id');
        $end_stage_id = $request->input('end_stage_id');
        $orderBy = $request->input('order_by', 'date_of_appeal');
        $orderDir = $request->input('order_dir', 'desc');
        $executor = $request->input('executor');
        $query = Appeal::whereIn('stage_id', $stages)
            ->with('stage')
            ->with('typeOfAppeal')
            ->with('receiptChannel')
            ->with('applicantIndividuals')
            ->with('applicantLegalEntities')
            ->with('applicantIndividuals.personInfo')
            ->with('personInterests.personInfo')
            ->with('typeOfAppealByCount')
            ->with('frequencyOfAppeal')
            ->with('typeOfAppealLanguage')
            ->with('characterOfQuestions')
            ->with('organizations')
            ->with('representativeIo')
            ->orderBy($orderBy, $orderDir);
        if ($registered) {
            $query->where(function ($query) use ($registered) {
                $query->whereNotNull('number');
            });
        }
        if($applicantSearch) {
            $query->whereHas('applicantIndividuals', function ($query) use ($applicantSearch) {
                $query->where('applicant_individuals.id', $applicantSearch)
                ->orWhereHas('personInfo', function ($query) use ($applicantSearch) {
                    $query->where('person_infos.name', 'ilike', '%' . $applicantSearch . '%')
                    ->orWhere('person_infos.last_name', 'ilike', '%' . $applicantSearch . '%')
                        ->orWhere('person_infos.patronymic', 'ilike', '%' . $applicantSearch . '%');
                });
            });

            $query->orWhereHas('applicantLegalEntities', function ($query) use ($search) {
                $query->where('name', 'ilike', '%' . $search . '%')
                    ->orWhere('name_kg', 'ilike', '%' . $search . '%')
                    ->orWhere('inn', 'ilike', '%' . $search . '%');
            });
        }
        if($personSearch) {
            $query->whereHas('personInterests', function ($query) use ($personSearch) {
                $query->where('applicant_individuals.id', $personSearch)
                    ->orWhereHas('personInfo', function ($query) use ($personSearch) {
                        $query->where('person_infos.name', 'ilike', '%' . $personSearch . '%')
                            ->orWhere('person_infos.last_name', 'ilike', '%' . $personSearch . '%')
                            ->orWhere('person_infos.patronymic', 'ilike', '%' . $personSearch . '%');
                    });
            });
                $query->orWhereHas('personInterestsLegal', function ($query) use ($search) {
                    $query->where('name', 'ilike', '%' . $search . '%')
                        ->orWhere('name_kg', 'ilike', '%' . $search . '%')
                        ->orWhere('inn', 'ilike', '%' . $search . '%');
                });

        }
        if ($startDate) {
            $query->where('date_of_appeal', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('date_of_appeal', '<=', $endDate);
        }

        if ($characterOfQuestion) {
            $query->whereHas('characterOfQuestions', function ($query) use ($characterOfQuestion) {
                $query->where('character_of_question_id', $characterOfQuestion);
            });
        }

        if ($description) {
            $query->where('description', 'ilike', '%' . $description . '%');
        }

        if ($number) {
            $query->where('number', 'ilike', '%' . $number . '%');
        }

        if ($appeal_language_id) {
            $query->whereHas('typeOfAppealLanguage', function ($query) use ($appeal_language_id) {
                $query->where('appeal_language_id', $appeal_language_id);
            });
        }

        if ($type_of_appeal_id) {
            $query->whereHas('typeOfAppeal', function ($query) use ($type_of_appeal_id) {
                $query->where('type_of_appeal_id', $type_of_appeal_id);
            });
        }

        if ($receipt_channel_id) {
            $query->whereHas('receiptChannel', function ($query) use ($receipt_channel_id) {
                $query->where('receipt_channel_id', $receipt_channel_id);
            });
        }

        if ($type_appeal_count_id) {
            $query->whereHas('typeOfAppealByCount', function ($query) use ($type_appeal_count_id) {
                $query->where('type_appeal_count_id', $type_appeal_count_id);
            });
        }
        if ($frequenciesy_of_appeal_id) {
            if ($frequenciesy_of_appeal_id === 'false') {
                $frequenciesy_of_appeal_id = 1;
            } else {
                $frequenciesy_of_appeal_id = 2;
            }
            $query->whereHas('frequencyOfAppeal', function ($query) use ($frequenciesy_of_appeal_id) {
                $query->where('frequenciesy_of_appeal_id', $frequenciesy_of_appeal_id);
            });
        }

        if ($organization_id) {
            $query->whereHas('organizations', function ($query) use ($organization_id) {
                $query->where('government_agency_id', $organization_id);
            });
        }

        if ($stage_id) {
            $query->whereHas('stage', function ($query) use ($stage_id) {
                $query->where('stage_id', $stage_id);
            });
        }

        if ($end_stage_id) {
            $query->whereHas('stage', function ($query) use ($end_stage_id) {
                $query->where('stage_id', $end_stage_id);
            });
        }

        if ($representative_io_id) {
            $query->whereHas('representativeIo', function ($query) use ($representative_io_id) {
                $query->where('representative_io_id', $representative_io_id);
            });
        }

        if ($executor) {
            $query->whereHas('appealDoers', function ($query) use ($executor) {
                $query->where('doers', 'like', '%"user_id":"' . $executor . '"%');
            });
        }

        if ($result) {
            if ($result == 1) {
                $query->where('result', true);
            } else if ($result == 2) {
                $query->where('result', false);
            }
        }

        $appeals = $query->paginate(10);

        return AppealListResource::collection($appeals);
    }

    /**
     * @OA\Get(
     *     path="/api/appeals/{id}/show",
     *     tags={"Обращения"},
     *     summary="Получение обращения",
     *     description="Получение обращения",
     *     operationId="show",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID обращения",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(ref="#/components/schemas/AppealShowResource")
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not found",
     *      ),
     *      @OA\Response(
     *          response=419,
     *          description="CSRF token mismatch"
     *      )
     * )
     */
    public function show(Appeal $appeal)
    {
        $read = $appeal->read ?? [];
        $stageId = $appeal->stage_id;
        $userId = Auth::id();
        $userRead = false;

        foreach ($read as $item) {
            if (in_array($userId, $item)) {
                $userRead = true;
            }
        }

        if (!$userRead) {
            $read[$stageId][] = $userId;
            $appeal->read = $read;
            $appeal->save();
        }

        return new AppealShowResource($appeal);
    }

    /**
     * @OA\Get(
     *     path="/api/appeals/dependencies",
     *     tags={"Обращения"},
     *     summary="Необходимые данные для обращений",
     *     description="Необходимые данные для обращений",
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(ref="#/components/schemas/AppealDependenciesResource")
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
    public function getDependenciesData()
    {
        return new AppealDependenciesResource([]);
    }

    /**
     * @OA\Post(
     *     path="/api/appeals",
     *     tags={"Обращения"},
     *     summary="Создание обращения",
     *     description="Создание обращения",
     *     operationId="appeals.store",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/AppealStoreRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(ref="#/components/schemas/AppealShowResource")
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
    public function store(AppealStoreRequest $request)
    {
        $data = $request->validated();
        $data['applicants'] = json_encode($data['applicants']);
        $data['stage_id'] = StageHistory::START_STAGE_ID;
        $data['frequenciesy_of_appeal_id'] = !empty($data['frequenciesy_of_appeal_id']) ? 2 : 1;
        $appeal = Appeal::create($data);
        $stageHistory = StageHistory::create([
            'appeal_id' => $appeal->id,
            'stage_id' => StageHistory::START_STAGE_ID,
            'user_id' => auth()->user()->id,
        ]);
        $data['applicant_individual_ids'] = [];
        $data['applicant_legal_entity_ids'] = [];
        $data['person_interest_ids'] = [];
        $data['legal_interest_ids'] = [];
        foreach($request->get('applicants') as $item) {
            if ($item['type'] == 'individual') {
                $data['applicant_individual_ids'][] = $item['id'];
            } else {
                $data['applicant_legal_entity_ids'][] = $item['id'];
            }
            if (!empty($item['personList'])) {
                foreach ($item['personList'] as $person) {
                    if ($person['type'] == 'individual') {
                        $profile = $person['profile'];
                        $profilePersonData = new ProfilePersonData();
                        $profilePersonData->a_individual_id = $person['id'];
                        $profilePersonData->appeal_id = $appeal->id;
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
                        $data['person_interest_ids'][$person['id']] = ['profile_id' => $profilePersonData->id];
                    } else {
                        $data['legal_interest_ids'][] = $person['id'];
                    }
                }
            }
        }

        $appeal->stageHistories()->save($stageHistory);
        if (!empty($data['applicant_individual_ids'])) {
            $appeal->applicantIndividuals()->sync($data['applicant_individual_ids']);
        }
        if (!empty($data['applicant_legal_entity_ids'])) {
            $appeal->applicantLegalEntities()->sync($data['applicant_legal_entity_ids']);
        }
        if (!empty($data['person_interest_ids'])) {
            $appeal->personInterests()->sync($data['person_interest_ids']);
        }
        if (!empty($data['legal_interest_ids'])) {
            $appeal->personInterests()->sync($data['legal_interest_ids']);
        }
        if (!empty($data['character_of_questions'])) {
            foreach ($data['character_of_questions'] as $characterOfQuestion) {
                $pivoteData = [
                    'court_id' => null,
                    'is_main' => $characterOfQuestion['is_main'],
                ];
                if (!empty($characterOfQuestion['court'])) {
                    if(!empty($characterOfQuestion['court']['id'])) {
                        $court = Court::findOrFail($characterOfQuestion['court']['id']);
                        $court->update($characterOfQuestion['court']);
                        $pivoteData['court_id'] = $court->id;
                    } else {
                        $pivoteData['court_id'] = Court::create($characterOfQuestion['court'])->id;
                    }
                }

                $appeal->characterOfQuestions()->attach($characterOfQuestion['character_of_question_id'], $pivoteData);
            }
        }
        if (!empty($data['category_appeal_ids'])) {
            $appeal->categoryAppeals()->sync($data['category_appeal_ids']);
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
            $appeal->organizations()->sync($organizationsIds);
        }

        return new AppealShowResource($appeal);
    }

    /**
     * @OA\POST(
     *     path="/api/appeals/{appealId}/{actionId}",
     *     tags={"Обращения"},
     *     summary="Обновление обращения",
     *     description="Обновление обращения",
     *     operationId="appeals.update",
     *     @OA\Parameter(
     *         name="appeal",
     *         in="path",
     *         description="ID обращения",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/AppealStoreRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(ref="#/components/schemas/AppealShowResource")
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
    public function update(Request $request, $appealId, $actionId)
    {
        $action = StageAction::findOrFail($actionId);
        $appeal = Appeal::findOrFail($appealId);
        $data = $request->all();
        $data['applicants'] = json_encode($data['applicants']);
        $data['frequenciesy_of_appeal_id'] = !empty($data['frequenciesy_of_appeal_id']) ? 2 : 1;
        $data['stage_id'] = $action->next_stage_id ?? $appeal->stage_id;
        $appeal->update($data);
        $stageHistoryData = [
            'appeal_id' => $appeal->id,
            'action_id' => $action->id,
            'stage_id' => $action->next_stage_id ?? $appeal->stage_id,
            'user_id' => auth()->user()->id,
        ];
        $stageHistory = StageHistory::create($stageHistoryData);
        $data['applicant_individual_ids'] = [];
        $data['applicant_legal_entity_ids'] = [];
        $data['person_interest_ids'] = [];
        $data['legal_interest_ids'] = [];
        foreach($request->get('applicants') as $item) {
            if ($item['type'] == 'individual') {
                $data['applicant_individual_ids'][] = $item['id'];
            } else {
                $data['applicant_legal_entity_ids'][] = $item['id'];
            }
            if (!empty($item['personList'])) {
                foreach ($item['personList'] as $person) {
                    if ($person['type'] == 'individual') {
                        $data['person_interest_ids'][] = $person['id'];
                    } else {
                        $data['legal_interest_ids'][] = $person['id'];
                    }
                }
            }
        }

        $appeal->stageHistories()->save($stageHistory);
        if (!empty($data['applicant_individual_ids'])) {
            $appeal->applicantIndividuals()->sync($data['applicant_individual_ids']);
        }
        if (!empty($data['applicant_legal_entity_ids'])) {
            $appeal->applicantLegalEntities()->sync($data['applicant_legal_entity_ids']);
        }
        if (!empty($data['person_interest_ids'])) {
            $appeal->personInterests()->sync($data['person_interest_ids']);
        }
        if (!empty($data['legal_interest_ids'])) {
            $appeal->personInterests()->sync($data['legal_interest_ids']);
        }
        if (!empty($data['character_of_questions'])) {
            $charactersOfQuestions = [];
            foreach ($data['character_of_questions'] as $characterOfQuestion) {
                $pivoteData = [
                    'court_id' => null,
                    'is_main' => $characterOfQuestion['is_main'],
                ];
                if (!empty($characterOfQuestion['court'])) {
                    if(!empty($characterOfQuestion['court']['id'])) {
                        $court = Court::findOrFail($characterOfQuestion['court']['id']);
                        $court->update($characterOfQuestion['court']);
                        $pivoteData['court_id'] = $court->id;
                    } else {
                        $pivoteData['court_id'] = Court::create($characterOfQuestion['court'])->id;
                    }
                }

                $charactersOfQuestions[$characterOfQuestion['character_of_question_id']] = $pivoteData;
            }
            $appeal->characterOfQuestions()->sync($charactersOfQuestions);
        }
        if (!empty($data['category_appeal_ids'])) {
            $appeal->categoryAppeals()->sync($data['category_appeal_ids']);
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
            $appeal->organizations()->sync($organizationsIds);
        }

        return new AppealShowResource($appeal);
    }

    /**
     * @OA\Delete(
     *     path="/api/appeals/{appeal}",
     *     tags={"Обращения"},
     *     summary="Удаление обращения",
     *     description="Удаление обращения",
     *     operationId="appeals.destroy",
     *     @OA\Parameter(
     *         name="appeal",
     *         in="path",
     *         description="ID обращения",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *     ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=419,
     *          description="CSRF token mismatch"
     *      ),
     * )
     */
    public function destroy($id)
    {
        $appeal = Appeal::findOrFail($id);
        $appeal->delete();

        return response()->json(['message' => 'Жалоба успешно удалена']);
    }

    /**
     * @OA\Post(
     *     path="/api/appeals/organization/{id}",
     *     tags={"Обращения"},
     *     summary="Добавление/удаление организаций в отношении которых жалоба",
     *     description="Добавление/удаление организаций в отношении которых жалоба",
     *     @OA\Parameter(
     *         name="appeal",
     *         in="path",
     *         description="ID обращения",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/OrganizationArrayRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(ref="#/components/schemas/OrganizationArrayResource")
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
    function updateOrCreateOrganization(OrganizationArrayRequest $request, $id)
    {
        $data = $request->validated();
        $appeal = Appeal::findOrfail($id);
        $organizations = $data['organizations'];
        $organizationsIds = [];
        foreach($organizations as $organizations) {
            if (!empty($organizations['defendents'])) {
                $organizationsIds[$organizations['id']] = ['defendent_ids' => json_encode($organizations['defendents'])];
            } else {
                $organizationsIds[] = $organizations['id'];
            }
        }
        $appeal->organizations()->sync($organizationsIds);

        return (OrganizationResource::collection($appeal->organizations))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * @OA\Get(
     *     path="/api/appeals/organization/{id}",
     *     tags={"Обращения"},
     *     summary="организаци в отношении которой жалоба",
     *     description="организаци в отношении которой жалоба",
     *     @OA\Parameter(
     *         name="appeal",
     *         in="path",
     *         description="ID обращения",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(ref="#/components/schemas/OrganizationArrayResource")
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
    public function getOrganization($id)
    {
        $appeal = Appeal::findOrfail($id);

        return (OrganizationResource::collection($appeal->organizations))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    public function updateFromAction(AppealUpdateFromActionRequest $request, $id, $actionId)
    {
        $appeal = Appeal::findOrfail($id);
        $lastStageHistory = $appeal->getLastStageHistory();
        $action = StageAction::find($actionId);
        $fields = $request->validated();
        $stage = Stage::find($action->next_stage_id);
        $prevStageId = $appeal->prev_stage_id;
        $stageAction = StageAction::find($actionId);
        $stageHistories = [
            'appeal_id' => $appeal->id,
            'action_id' => $action->id,
            'stage_id' => $action->next_stage_id,
            'prev_stage_id' => $appeal->stage_id,
            'prev_stage_history_id' => !empty($lastStageHistory) ? $lastStageHistory->id : 1,
            'user_id' => auth()->user()->id,
        ];
        $appeals = [
            'stage_id' => $action->next_stage_id,
        ];
        $appealsHistory = [];

        foreach ($fields as $key => $value) {
            [$tableName, $fieldName] = explode('-', $key);
            ${$tableName}[$fieldName] = $value;
            $appealsHistory['appeals'][$fieldName] = $appeal->{$fieldName};
        }
        $stageHistories['fields_history'] = json_encode($appealsHistory);
        StageHistory::create($stageHistories);
        $appeal->update($appeals);

        if ($stage->end_stage) {
            StageHistory::create([
                'appeal_id' => $appeal->id,
                'action_id' => $action->id,
                'stage_id' => 9,
                'prev_stage_id' => $appeal->stage_id,
                'prev_stage_history_id' => $lastStageHistory->id,
                'user_id' => auth()->user()->id,
            ]);
            $appeal->update(['stage_id' => 9]);
        }
        if ($request->has('appealDate-date')) {
            AppealDate::create([
                'appeal_id' => $appeal->id,
                'date' => $request->input('appealDate-date'),
                'user_id' => auth()->user()->id,
            ]);
        }
        $read = $appeal->read;
        if ($stageAction->return) {
           $users = User::getPermittedUsers($appeal);

           foreach ($users as $user) {
               foreach ($read as $stageId => $item) {
                   if (in_array($user->id, $item)) {
                       if (($key = array_search($user->id, $item)) !== false) {
                           unset($read[$stageId][$key]);
                       }
                   }
               }
           }
        } else {
            unset($read[$stage->id]);
        }

        $appeal->read = $read;
        $appeal->save();

        if ($stageAction->return) {
            $messageData = new GetAppealDataForWebNotification($appeal);
            $notificationService = new WebNotification($messageData, $appeal);
            $notificationService->sendNotificationOnUpdate();
        }

        return new AppealShowResource($appeal);
    }

    public function dawngradeFromAction($id, $actionId)
    {
        $appeal = Appeal::findOrfail($id);
        $action = StageAction::find($actionId);
        $lastStageHistory = $appeal->getLastStageHistory();
        $stageHistoryForRevert = StageHistory::find($lastStageHistory->prev_stage_history_id);

        $newStageHistory = $stageHistoryForRevert->replicate();
        $newStageHistory->action_id = $action->id;
        $newStageHistory->user_id = auth()->user()->id;
        $newStageHistory->save();

        $appeal->stage_id = $stageHistoryForRevert->stage_id;
        $appeal->save();
        $fieldsHistory = json_decode($lastStageHistory->fields_history, true);
        if (!empty($fieldsHistory['appeals'])) {
            $appeals = $fieldsHistory['appeals'];
            $appeal->update($appeals);
        }

        return new AppealShowResource($appeal);
    }

    public function createAppealDate(AppealDateRequest $request, $id)
    {
        $data = $request->validated();
        $appeal = Appeal::findOrfail($id);
        $appealDate = AppealDate::create([
            'appeal_id' => $appeal->id,
            'date' => $data['date'],
            'user_id' => auth()->user()->id,
        ]);

        return $appealDate;
    }

    public function createAppealDoer(AppealDoerRequest $request, $id)
    {
        $data = $request->validated();
        $appeal = Appeal::findOrfail($id);
        $appealDoer = AppealDoer::create([
            'appeal_id' => $appeal->id,
            'doers' => json_encode($data['doers']),
            'user_id' => auth()->user()->id,
        ]);
        $appealDoer->save();

        $doers = json_decode($appealDoer->doers, true) ?? [];
        foreach ($doers as $key => $doer) {
            $doers[$key]['user'] = User::find($doer['user_id'])->load('organizationEmployee');
        }

        return json_encode($doers);
    }

    public function setAppealStructureCategories(AppealStructureCategoriesRequest $request, $id)
    {
        $data = $request->validated();
        $appeal = Appeal::findOrfail($id);
        $appeal->structure_categories = json_encode($data['categories']);
        $appeal->save();

        return $data['categories'];
    }

    public function setAttachedAppeals(AttachedAppealsRequest $request, $id)
    {
        $data = $request->validated();
        $appeal = Appeal::findOrfail($id);
        $appeal->attached_appeals = json_encode($data['attached_appeals']);
        $appeal->save();

        $appeals = Appeal::whereIn('id', $data['attached_appeals'])->get();

        return AppealListResource::collection($appeals);
    }

    public function setActions(CaseActionsRequest $request, $id)
    {
        $data = $request->validated();
        $appeal = Appeal::findOrFail($id);
        $actionId = $data['action_id'];
        $appealAction = CaseAction::query()->where('action_id', $actionId)->where('appeal_id', $id)->first();
        if (!$appealAction) {
            $appealAction = new CaseAction();
            $appealAction->appeal_id = $id;
            $appealAction->action_id = $actionId;
        }
        $appealAction->date = $data['date'];
        $appealAction->description = $data['description'];
        $appealAction->place = $data['place'];
        $appealAction->executors = json_encode($data['executors']);
        $appealAction->save();
        $appealActions = $appeal->appealActions()->get();

        return CaseActionResource::collection($appealActions);
    }

    public function deleteAppealAction($id) {
        $appealAction = CaseAction::findOrFail($id);
        $appeal = $appealAction->appeal;
        $appealAction->delete();

        $actions = $appeal->appealActions()->get();

        return CaseActionResource::collection($actions);
    }

    public function updateReadStatus(AppealReadRequest $request, Appeal $appeal)
    {
        $data = $request->validated();
        $readData = $appeal->read;
        if ($data['readStatus'] && !isset($readData[$appeal->stage_id])) {
            $readData[$appeal->stage_id] = [];
        }

        $index = array_search(Auth::id(), $readData[$appeal->stage_id]);

        if ($data['readStatus'] && $index === false) {
            $readData[$appeal->stage_id][] = Auth::id();
        } elseif (!$data['readStatus'] && $index !== false) {
            unset($readData[$appeal->stage_id][$index]);
        }
        $appeal->read = $readData;
        $appeal->save();

        return new AppealListResource($appeal);
    }


    public function setUsersToFavorite($id)
    {
        $userId = Auth::id();
        $appeal = Appeal::findOrFail($id);
        $favoriteUsers = $appeal->favorite ?? [];
        $index = array_search($userId, $favoriteUsers);

        if ($index !== false) {
            unset($favoriteUsers[$index]);
        } else {
            $favoriteUsers[] = $userId;
        }

        $appeal->favorite = array_values($favoriteUsers);
        $appeal->save();

        return new AppealListResource($appeal);
    }
}
