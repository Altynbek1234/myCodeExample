<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfilePersonData\ProfilePersonDataRequest;
use App\Http\Resources\ProfilePersonData\ProfilePersonDataResource;
use App\Models\ApplicantIndividual;
use App\Models\ProfilePersonData;
use Symfony\Component\HttpFoundation\Response;

class ProfilePersonDataController extends Controller
{
    /**
     * @OA\Post(
     *      path="/api/profile_person_data",
     *      tags={"Данные профиля лица"},
     *      summary="Создание данных для профиля лица",
     *      description="Создание данных для профиля лица",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/ProfilePersonDataRequest")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/ProfilePersonDataResource")
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
    public function store(ProfilePersonDataRequest $request)
    {
        $request->validated();
        $profilePersonData = new ProfilePersonData();
        $profilePersonData->a_individual_id = $request->get('a_individual_id');
        //$profilePersonData->appeal_id = $request->get('appeal_id');
        if (is_int($request->get('age')) && $request->get('age') != 80001 && $request->get('age') != 80000) {
            $profilePersonData->age = $request->get('age');
        }
        if (!is_numeric($request->get('nationality'))) {
            $profilePersonData->other_nationality = $request->get('nationality');
        } else {
            $profilePersonData->nationality_id = $request->get('nationality');
        }

        if (!is_numeric($request->get('social_status'))) {
            $profilePersonData->other_social_status = $request->get('social_status');
        } else {
            $profilePersonData->social_status_id = $request->get('social_status');
        }

        if (!is_numeric($request->get('family_status'))) {
            $profilePersonData->other_family_status = $request->get('family_status');
        } else {
            $profilePersonData->family_status_id = $request->get('family_status');
        }

        if (!is_numeric($request->get('level_of_education'))) {
            $profilePersonData->other_level_of_education = $request->get('level_of_education');
        } else {
            $profilePersonData->level_of_education_id = $request->get('level_of_education');
        }

        if (!is_numeric($request->get('income_levels'))) {
            $profilePersonData->other_income_levels = $request->get('income_levels');
        } else {
            $profilePersonData->income_levels_id = $request->get('income_levels');
        }

        if (!is_numeric($request->get('migration_status'))) {
            $profilePersonData->other_migration_status = $request->get('migration_status');
        } else {
            $profilePersonData->migration_status_id = $request->get('migration_status');
        }

        if (!is_numeric($request->get('purpose_of_migrant'))) {
            $profilePersonData->other_purpose_of_migrant = $request->get('purpose_of_migrant');
        } else {
            $profilePersonData->purpose_of_migrant_id = $request->get('purpose_of_migrant');
        }

        if (!is_numeric($request->get('getting_disabilities'))) {
            $profilePersonData->other_getting_disabilities = $request->get('getting_disabilities');
        } else {
            $profilePersonData->getting_disabilities_id = $request->get('getting_disabilities');
        }

        if (!is_numeric($request->get('limited_health'))) {
            $profilePersonData->other_limited_healths = $request->get('limited_health');
        } else {
            $profilePersonData->limited_health_id = $request->get('limited_health');
        }

        if (!is_numeric($request->get('sickness'))) {
            $profilePersonData->other_sickness = $request->get('sickness');
        } else {
            $profilePersonData->sickness_id = $request->get('sickness');
        }
        $registered_psychiatric = $request->get('registered_psychiatric');
        $date = \DateTime::createFromFormat('d-m-Y', $registered_psychiatric);

        if ($date && $date->format('d-m-Y') === $registered_psychiatric) {
            $profilePersonData->registered_psychiatric = 1;
            $profilePersonData->date_registered_psychiatric = $request->get('registered_psychiatric');
        } else {
            if (!is_numeric($request->get('registered_psychiatric'))) {
                $profilePersonData->other_registered_psychiatric = $request->get('registered_psychiatric');
            }
            if ($request->get('registered_psychiatric') == 80000) {
                $profilePersonData->registered_psychiatric = $request->get('registered_psychiatric');
            }
            if ($request->get('registered_psychiatric') == 80001) {
                $profilePersonData->registered_psychiatric = $request->get('registered_psychiatric');
            }
        }

        if (!is_numeric($request->get('criminal_record'))) {
            $profilePersonData->other_criminal_record = $request->get('criminal_record');
        } else {
            $profilePersonData->criminal_record = $request->get('criminal_record');
        }

        if (!is_numeric($request->get('vulnerable_group'))) {
            $profilePersonData->other_vulnerable_group = $request->get('vulnerable_group');
        } else {
            $profilePersonData->vulnerable_group_id = $request->get('vulnerable_group');
        }

        if (!is_numeric($request->get('group_membership'))) {
            $profilePersonData->other_group_memberships = $request->get('group_membership');
        } else {
            $profilePersonData->group_membership_id = $request->get('group_membership');
        }

        if ($request->get('note')) {
            $profilePersonData->note = $request->get('note');
        }

        $profilePersonData->save();

        return (new ProfilePersonDataResource($profilePersonData))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * @OA\Put(
     *      path="/api/profile_person_data/{id}",
     *      tags={"Данные профиля лица"},
     *      summary="Обновление данных профиля лица",
     *      description="Обновление данных",
     *          @OA\RequestBody(
     *              required=true,
     *              @OA\JsonContent(ref="#/components/schemas/ProfilePersonDataRequest")
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
     *              @OA\JsonContent(ref="#/components/schemas/ProfilePersonDataResource")
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
    public function update(ProfilePersonDataRequest $request, $id)
    {
        $request->validated();
        $profilePersonData = ProfilePersonData::findOrfail($id);;
        $profilePersonData->a_individual_id = $request->get('a_individual_id');
        //$profilePersonData->appeal_id = $request->get('appeal_id');
        if ($request->get('unknown_age')) {
            $profilePersonData->age = null;
        }
        if ($request->get('not_wont_age')) {
            $profilePersonData->age = 0;
        }
        if ($request->get('age')) {
            $profilePersonData->age = $request->get('age');
        }
        if ($request->get('nationality_id')) {
            $profilePersonData->nationality_id = $request->get('nationality_id');
        }
        if ($request->get('unknown_nationality')) {
            $profilePersonData->nationality_id = 80000; //значение зарезервированно неизвестно
        }
        if ($request->get('not_wont_nationality')) {
            $profilePersonData->nationality_id = 80001; //значение зарезервированно не желает
        }
        if ($request->get('other_nationality')) {
            $profilePersonData->other_nationality = $request->get('other_nationality');
        }
        if ($request->get('social_status_id')) {
            $profilePersonData->social_status_id = $request->get('social_status_id');
        }
        if ($request->get('unknown_social_status')) {
            $profilePersonData->social_status_id = 80000; //значение зарезервированно неизвестно
        }
        if ($request->get('not_wont_social_status')) {
            $profilePersonData->social_status_id = 80001; //значение зарезервированно не желает
        }
        if ($request->get('other_social_status')) {
            $profilePersonData->other_social_status = $request->get('other_social_status');
        }
        if ($request->get('family_status_id')) {
            $profilePersonData->family_status_id = $request->get('family_status_id');
        }
        if ($request->get('unknown_family_status')) {
            $profilePersonData->family_status_id = 80000; //значение зарезервированно неизвестно
        }
        if ($request->get('not_wont_family_status')) {
            $profilePersonData->family_status_id = 80001; //значение зарезервированно не желает
        }
        if ($request->get('other_family_status')) {
            $profilePersonData->other_family_status = $request->get('other_family_status');
        }
        if ($request->get('level_of_education_id')) {
            $profilePersonData->level_of_education_id = $request->get('level_of_education_id');
        }
        if ($request->get('unknown_level_of_education')) {
            $profilePersonData->level_of_education = 80000; //значение зарезервированно неизвестно
        }
        if ($request->get('not_wont_level_of_education')) {
            $profilePersonData->level_of_education_id = 80001; //значение зарезервированно не желает
        }
        if ($request->get('other_level_of_education')) {
            $profilePersonData->other_level_of_education = $request->get('other_level_of_education');
        }
        if ($request->get('income_levels_id')) {
            $profilePersonData->income_levels_id = $request->get('income_levels_id');
        }
        if ($request->get('unknown_income_level')) {
            $profilePersonData->income_levels_id = 80000; //значение зарезервированно неизвестно
        }
        if ($request->get('not_wont_income_level')) {
            $profilePersonData->income_levels_id = 80001; //значение зарезервированно не желает
        }
        if ($request->get('other_income_level')) {
            $profilePersonData->other_income_levels = $request->get('other_income_level');
        }
        if ($request->get('migration_status_id')) {
            $profilePersonData->migration_status_id = $request->get('migration_status_id');
        }
        if ($request->get('unknown_migration_status')) {
            $profilePersonData->migration_status_id = 80000; //значение зарезервированно неизвестно
        }
        if ($request->get('not_wont_migration_status')) {
            $profilePersonData->migration_status_id = 80001; //значение зарезервированно не желает
        }
        if ($request->get('other_migration_status')) {
            $profilePersonData->other_migration_status = $request->get('other_migration_status');
        }
        if ($request->get('purpose_of_migrant_id')) {
            $profilePersonData->purpose_of_migrant_id = $request->get('purpose_of_migrant_id');
        }
        if ($request->get('unknown_purpose_of_migrant')) {
            $profilePersonData->purpose_of_migrant_id = 80000; //значение зарезервированно неизвестно
        }
        if ($request->get('not_wont_purpose_of_migrant')) {
            $profilePersonData->purpose_of_migrant_id = 80001; //значение зарезервированно не желает
        }
        if ($request->get('other_purpose_of_migrant')) {
            $profilePersonData->other_purpose_of_migrant = $request->get('other_purpose_of_migrant');
        }
        if ($request->get('getting_disabilities_id')) {
            $profilePersonData->getting_disabilities_id = $request->get('getting_disabilities_id');
        }
        if ($request->get('unknown_getting_disabilities')) {
            $profilePersonData->getting_disabilities_id = 80000; //значение зарезервированно неизвестно
        }
        if ($request->get('not_wont_getting_disabilities')) {
            $profilePersonData->getting_disabilities_id = 80001; //значение зарезервированно не желает
        }
        if ($request->get('other_getting_disabilities')) {
            $profilePersonData->other_getting_disabilities = $request->get('other_getting_disabilities');
        }
        if ($request->get('limited_health_id')) {
            $profilePersonData->limited_health_id = $request->get('limited_health_id');
        }
        if ($request->get('unknown_limited_health')) {
            $profilePersonData->limited_health_id = 80000; //значение зарезервированно неизвестно
        }
        if ($request->get('not_wont_limited_health')) {
            $profilePersonData->limited_health_id = 80001; //значение зарезервированно не желает
        }
        if ($request->get('other_limited_healths')) {
            $profilePersonData->other_limited_healths = $request->get('other_limited_healths');
        }
        if ($request->get('sickness_id')) {
            $profilePersonData->sickness_id = $request->get('sickness_id');
        }
        if ($request->get('unknown_sickness')) {
            $profilePersonData->sickness_id = 80000; //значение зарезервированно неизвестно
        }
        if ($request->get('not_wont_sickness')) {
            $profilePersonData->sickness_id = 80001; //значение зарезервированно не желает
        }
        if ($request->get('other_sickness')) {
            $profilePersonData->other_sickness = $request->get('other_sickness');
        }
        if ($request->get('registered_psychiatric')) {
            $profilePersonData->registered_psychiatric = $request->get('registered_psychiatric');
        }
        if ($request->get('unknown_registered_psychiatric')) {
            $profilePersonData->unknown_registered_psychiatric = $request->get('unknown_registered_psychiatric');
        }
        if ($request->get('not_wont_registered_psychiatric')) {
            $profilePersonData->not_wont_registered_psychiatric = $request->get('not_wont_registered_psychiatric');
        }
        if ($request->get('registered_psychiatric') && $request->get('date_registered_psychiatric')) {
            $profilePersonData->date_registered_psychiatric = $request->get('date_registered_psychiatric');
        }
        if ($request->get('other_registered_psychiatric')) {
            $profilePersonData->other_registered_psychiatric = $request->get('other_registered_psychiatric');
        }
        if ($request->get('criminal_record')) {
            $profilePersonData->criminal_record = $request->get('criminal_record');
        }
        if ($request->get('unknown_criminal_record')) {
            $profilePersonData->unknown_criminal_record = $request->get('unknown_criminal_record');
        }
        if ($request->get('not_wont_criminal_record')) {
            $profilePersonData->not_wont_criminal_record = $request->get('not_wont_criminal_record');
        }
        if ($request->get('other_criminal_record')) {
            $profilePersonData->other_criminal_record = $request->get('other_criminal_record');
        }
        if ($request->get('vulnerable_group_id')) {
            $profilePersonData->vulnerable_group_id = $request->get('vulnerable_group_id');
        }
        if ($request->get('unknown_vulnerable_group')) {
            $profilePersonData->vulnerable_group_id = 80000; //значение зарезервированно неизвестно
        }
        if ($request->get('not_vulnerable_group')) {
            $profilePersonData->vulnerable_group_id = 80001; //значение зарезервированно не желает
        }
        if ($request->get('other_vulnerable_group')) {
            $profilePersonData->other_vulnerable_group = $request->get('other_vulnerable_group');
        }
        if ($request->get('group_membership_id')) {
            $profilePersonData->group_membership_id = $request->get('group_membership_id');
        }
        if ($request->get('unknown_group_membership')) {
            $profilePersonData->group_membership_id = 80000; //значение зарезервированно неизвестно
        }
        if ($request->get('not_group_membership')) {
            $profilePersonData->group_membership_id = 80001; //значение зарезервированно не желает
        }
        if ($request->get('other_group_memberships')) {
            $profilePersonData->group_membership_id = $request->get('other_group_memberships');
        }
        if ($request->get('note')) {
            $profilePersonData->note = $request->get('note');
        }

        $profilePersonData->update();

        return (new ProfilePersonDataResource($profilePersonData))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * @OA\Delete(
     *     path="/api/profile_person_data/{id}",
     *     tags={"Данные профиля лица"},
     *     summary="Удаление данных профиля лица",
     *     description="Удаление данных",
     *     @OA\Parameter(
     *         name="id",
     *         description="ID лица",
     *         required=true,
     *         in="path",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Successful operation"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     ),
     *     @OA\Response(
     *         response=419,
     *         description="CSRF token mismatch"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server Error"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden"
     *     )
     * )
     */
    public function destroy($id)
    {
        $profilePersonData = ProfilePersonData::findOrFail($id);
        $profilePersonData->delete();
        return response()->json(null, 204);
    }


    /**
     * @OA\Get (
     *     path="/api/profile_person_by_indiv/{id}",
     *     tags={"Данные профиля лица"},
     *     summary="Получение данных по id физ лица",
     *     description="Получение данных по id физ лица",
     *     @OA\Parameter(
     *         name="id",
     *         description="ID лица",
     *         required=true,
     *         in="path",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *         @OA\Response(
     *              response=200,
     *              description="Successful operation",
     *              @OA\JsonContent(ref="#/components/schemas/ProfilePersonDataResource")
     *          ),
     *     @OA\Response(
     *         response=204,
     *         description="Successful operation"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     ),
     *     @OA\Response(
     *         response=419,
     *         description="CSRF token mismatch"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server Error"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden"
     *     )
     * )
     */
    public function getByApplicantIndivId($id)
    {
        $applicantIndiv = ApplicantIndividual::findOrFail($id);
        $profilePersonData = $applicantIndiv->profile;

        return (new ProfilePersonDataResource($profilePersonData))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }


}
