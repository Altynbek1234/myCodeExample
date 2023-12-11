<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Soate;

class SoateController extends Controller
{
    /**
     * @OA\Get(
     *      path="api/soate-json",
     *      tags={"Json обьект населенных пунктов"},
     *      summary="Json обьект населенных пунктов",
     *      description="Json обьект населенных пунктов",
     *          @OA\Response(
     *              response=200,
     *              description="Successful operation",
     *              @OA\JsonContent(ref="#/components/schemas/SoateResource")
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
    public function getSoates()
    {
        $soates = Soate::with('children')->whereNull('parent_id')->get();

        $soateArray = $soates->map(function ($soate) {
            return [
                'id' => $soate->id,
                'label' => $soate->name,
                'children' => $soate->children->map(function ($child) {
                    return [
                        'id' => $child->id,
                        'label' => $child->name,
                        'children' => $child->children,
                    ];
                })->toArray(),
            ];
        })->toArray();

        return response()->json(['soates' => $soateArray]);
    }
}
