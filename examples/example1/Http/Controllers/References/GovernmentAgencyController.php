<?php

namespace App\Http\Controllers\References;

use App\Http\Controllers\Controller;
use App\Http\Resources\References\GovernmentAgencyResource;
use App\Models\GovernmentAgency;
use Symfony\Component\HttpFoundation\Response;

class GovernmentAgencyController extends Controller
{
    /**
     * @OA\Get(
     *      path="api/reference/government_agency",
     *      tags={"Cправочники"},
     *      summary="Справочник государственных органов",
     *      description="Справочник государственных органов",
     *      @OA\Response(
     *          response=200,
     *          description="Успешная операция",
     *          @OA\JsonContent(ref="#/components/schemas/GovernmentAgencyResource")
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Неавторизован",
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Не найдено"
     *      ),
     *      @OA\Response(
     *          response=419,
     *          description="Несоответствие CSRF токена"
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Запрещено"
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Ошибка сервера"
     *      )
     * )
     */
    public function index()
    {
        $governmentAgencies = GovernmentAgency::whereNull('parent_id')->get();

        $organizations = $governmentAgencies->map(function ($governmentAgency) {
            return [
                'id' => $governmentAgency->id,
                'name_ru' => $governmentAgency->name_ru,
                'name_kg' => $governmentAgency->name_kg,
                'code' => $governmentAgency->code,
                'children' => $this->getChildren($governmentAgency),
            ];
        })->toArray();

        return response()->json(['organizations' => $organizations]);
    }

    protected function getChildren($governmentAgency)
    {
        $children = $governmentAgency->children;
        if ($children->isEmpty()) {
            return [];
        }

        return $children->map(function ($child) {
            return [
                'id' => $child->id,
                'name_ru' => $child->name_ru,
                'name_kg' => $child->name_kg,
                'code' => $child->code,
                'children' => $this->getChildren($child),
            ];
        })->toArray();
    }
}
