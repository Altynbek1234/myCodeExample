<?php
namespace App\Http\Controllers;

use App\Services\SamoTourService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SamoTourController extends Controller
{
    protected SamoTourService $apiService;

    public function __construct(SamoTourService $apiService)
    {
        $this->apiService = $apiService;
    }

    public function getReferences($state, $townFrom): array
    {
        return [
            'townFroms' => $this->apiService->getTownFroms(),
            'states' => $this->apiService->getStates($townFrom),
            'nights' => $this->apiService->getNights(),
            'stars' => $this->apiService->getStars($state, $townFrom),
            'hotelTypes' => $this->apiService->getHotelTypes($state, $townFrom),
            'meals' => $this->apiService->getMeals($state, $townFrom),
            'hotels' => $this->apiService->getHotels($state, $townFrom),
            'currencies' => $this->apiService->getCurrencies($state, $townFrom),
            'towns' => $this->apiService->getTowns($state, $townFrom),
            'freightTypes' => $this->apiService->getFreightTypes($state, $townFrom),
            'tours' => $this->apiService->getTours($state, $townFrom),
        ];
    }

    public function search(Request $request): JsonResponse
    {
        $data = $this->getReferences($request->query->get('state'), $request->query->get('townFrom'));
        return response()->json($data);
    }

    public function find(Request $request): JsonResponse
    {
        $data = $this->apiService->getPrices(
            $request->request->get('state'),
            $request->request->get('townFrom'),
            $request->request->get('towns'),
            $request->request->get('currency'),
            $request->request->get('hotelTypes'),
            $request->request->get('meal'),
            $request->request->get('freightType'),
            $request->request->get('adult'),
            $request->request->get('child'),
            $request->request->get('hotels'),
            $request->request->get('costMin'),
            $request->request->get('costMax'),
            $request->request->get('dateFrom'),
            $request->request->get('dateTo'),
            $request->request->get('tours'),
            $request->request->get('nightsFrom'),
            $request->request->get('nightsTo'),
        );
        return response()->json($data);
    }
}
