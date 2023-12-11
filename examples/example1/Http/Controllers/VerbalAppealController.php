<?php

namespace App\Http\Controllers;

use App\Http\Requests\Appeal\AppealStoreRequest;
use App\Http\Requests\Appeal\AppealUpdateFromActionRequest;
use App\Http\Requests\VerbalAppeal\VerbalAppealStoreRequest;
use App\Http\Requests\VerbalAppeal\VerbalAppealUpdateFromActionRequest;
use App\Http\Resources\Appeal\AppealListResource;
use App\Http\Resources\VerbalAppeal\VerbalAppealListResource;
use App\Http\Resources\VerbalAppeal\VerbalAppealShowResource;
use App\Models\Court;
use App\Models\ProfilePersonData;
use App\Models\Stage;
use App\Models\StageAction;
use App\Models\StageHistory;
use App\Models\VerbalAppeal;
use Illuminate\Http\Request;

class VerbalAppealController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/verbal-appeals",
     *     tags={"Устные Обращения"},
     *     summary="Получение списка устных обращений",
     *     description="Получение списка устных обращений",
     *     operationId="index_verbal",
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
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $characterOfQuestion = $request->input('character_of_question');
        $description = $request->input('description');
        $number = $request->input('number');
        $languageId = $request->input('appeal_language_id');
        $applicantSearch = $request->input('applicant');
        $personSearch = $request->input('person');
        $stage_id = $request->input('stage_id');
        $employee = $request->input('employee_id');
        $writtenAppealHas = $request->input('written_appeal_has');
        $orderBy = $request->input('order_by', 'date_of_appeal');
        $orderDir = $request->input('order_dir', 'desc');
        $query = VerbalAppeal::query()->orderBy($orderBy, $orderDir);;

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
            $query->whereHas('appealCharacterOfQuestions', function ($query) use ($characterOfQuestion) {
                $query->where('character_of_question_id', $characterOfQuestion);
            });
        }

        if ($description) {
            $query->where('description', 'ilike', '%' . $description . '%');
        }

        if ($number) {
            $query->where('number', 'ilike', '%' . $number . '%');
        }

        if ($languageId) {
            $query->where('appeal_language_id', $languageId);
        }

        if ($stage_id) {
            $query->whereHas('stage', function ($query) use ($stage_id) {
                $query->where('stage_id', $stage_id);
            });
        }

        if ($employee) {
            $query->whereHas('employee', function ($query) use ($employee) {
                $query->where('employee_id', $employee);
            });
        }

        if ($writtenAppealHas) {
            $query->where('written_appeal_has', $writtenAppealHas);
        }

        $appeals = $query->paginate(10);;

        return VerbalAppealListResource::collection($appeals);
    }



    /**
     * @OA\Post(
     *     path="/api/verbal-appeals",
     *     tags={"Устные Обращения"},
     *     summary="Создание обращения",
     *     description="Создание обращения",
     *     operationId="verbalappeals.store",
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
    public function store(VerbalAppealStoreRequest $request)
    {
        $data = $request->validated();
        $data['applicants'] = json_encode($data['applicants']);
        $data['stage_id'] = StageHistory::START_STAGE_VERBAL_ID;
        $appealVerbal = VerbalAppeal::create($data);
        $stageHistory = StageHistory::create([
            'verbal_appeal_id' => $appealVerbal->id,
            'stage_id' => StageHistory::START_STAGE_VERBAL_ID,
            'user_id' => auth()->user()->id,
        ]);
        $data['applicant_individual_ids'] = [];
        $data['applicant_legal_entity_ids'] = [];
        $data['person_interest_ids'] = [];
        $data['legal_interest_ids'] = [];
        foreach ($request->get('applicants') as $item) {
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
                        $profilePersonData->verbal_appeal_id = $appealVerbal->id;
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

        $appealVerbal->stageHistories()->save($stageHistory);
        if (!empty($data['applicant_individual_ids'])) {
            $appealVerbal->applicantIndividuals()->sync($data['applicant_individual_ids']);
        }
        if (!empty($data['applicant_legal_entity_ids'])) {
            $appealVerbal->applicantLegalEntities()->sync($data['applicant_legal_entity_ids']);
        }
        if (!empty($data['person_interest_ids'])) {
            $appealVerbal->personInterests()->sync($data['person_interest_ids']);
        }
        if (!empty($data['legal_interest_ids'])) {
            $appealVerbal->personInterests()->sync($data['legal_interest_ids']);
        }
        if (!empty($data['character_of_questions'])) {
            foreach ($data['character_of_questions'] as $characterOfQuestion) {
                $pivoteData = [
                    'court_id' => null,
                    'is_main' => $characterOfQuestion['is_main'],
                ];
                if (!empty($characterOfQuestion['court'])) {
                    if (!empty($characterOfQuestion['court']['id'])) {
                        $court = Court::findOrFail($characterOfQuestion['court']['id']);
                        $court->update($characterOfQuestion['court']);
                        $pivoteData['court_id'] = $court->id;
                    } else {
                        $pivoteData['court_id'] = Court::create($characterOfQuestion['court'])->id;
                    }
                }

                $appealVerbal->characterOfQuestions()->attach($characterOfQuestion['character_of_question_id'], $pivoteData);
            }
        }

        return new VerbalAppealShowResource($appealVerbal);
    }

    /**
     * @OA\POST(
     *     path="/api/verbal-appeals/{appealId}/{actionId}",
     *     tags={"Устные Обращения"},
     *     summary="Обновление обращения",
     *     description="Обновление обращения",
     *     operationId="verbal-appeals.update",
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
     *         @OA\JsonContent(ref="#/components/schemas/VerbalAppealStoreRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(ref="#/components/schemas/VerbalAppealShowResource")
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
        $appeal = VerbalAppeal::findOrFail($appealId);
        $data = $request->all();
        $data['applicants'] = json_encode($data['applicants']);
        $appeal->update($data);
        $data['applicant_individual_ids'] = [];
        $data['applicant_legal_entity_ids'] = [];
        $data['person_interest_ids'] = [];
        $data['legal_interest_ids'] = [];
        $stageHistoryData = [
            'verbal_appeal_id' => $appeal->id,
            'action_id' => $action->id,
            'stage_id' => $action->next_stage_id ?? $appeal->stage_id,
            'user_id' => auth()->user()->id,
        ];
        $stageHistory = StageHistory::create($stageHistoryData);
        foreach ($request->get('applicants') as $item) {
            if ($item['type'] == 'individual') {
                $data['applicant_individual_ids'][] = $item['id'];
            } else {
                $data['applicant_legal_entity_ids'][] = $item['id'];
            }

            if (!empty($item['personList'])) {
                foreach ($item['personList'] as $person) {
                    if ($person['type'] == 'individual') {
                        $profile = $person['profile'];
                        if (!empty($profile['id'])) {
                            $profilePersonData = ProfilePersonData::findOrFail($profile['id']);
                        } else {
                            $profilePersonData = new ProfilePersonData();
                        }
                        $profilePersonData->a_individual_id = $person['id'];
                        $profilePersonData->verbal_appeal_id = $appeal->id;
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
                    if (!empty($characterOfQuestion['court']['id'])) {
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

        return new VerbalAppealShowResource($appeal);

    }

    /**
     * @OA\Get(
     *     path="/api/verbal-appeals/{id}/show",
     *     tags={"Устные Обращения"},
     *     summary="Получение обращения",
     *     description="Получение обращения",
     *     operationId="show-verbal",
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
    public function show(VerbalAppeal $appeal)
    {
        return new VerbalAppealShowResource($appeal);
    }

    public function updateFromAction(VerbalAppealUpdateFromActionRequest $request, $id, $actionId)
    {
        $appeal = VerbalAppeal::findOrfail($id);
        $lastStageHistory = $appeal->getLastStageHistory();
        $action = StageAction::find($actionId);
        $fields = $request->validated();
        $stage = Stage::find($action->next_stage_id);
        $stageHistories = [
            'verbal_appeal_id' => $appeal->id,
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

        return new VerbalAppealShowResource($appeal);
    }


    public function dawngradeFromAction($id, $actionId)
    {
        $appeal = VerbalAppeal::findOrfail($id);
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

        return new VerbalAppealShowResource($appeal);
    }

}
