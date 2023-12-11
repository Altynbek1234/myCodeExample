<?php

namespace App\Http\Controllers\References;

use App\Http\Controllers\Controller;
use App\Http\Resources\References\ReceiptChannelResource;
use App\Models\ReceiptChannel;
use Symfony\Component\HttpFoundation\Response;

class ReceiptChannelController extends Controller
{
    // Каналы поступления письменных обращений
    /**
     * @OA\Get(
     *      path="api/reference/receipt_channel",
     *      tags={"Cправочники"},
     *      summary="Каналы поступления письменных обращений",
     *      description="Каналы поступления письменных обращений",
     *          @OA\Response(
     *              response="200",
     *              description="Returns the  collection",
     *              @OA\JsonContent(
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/ReceiptChannelsResource")
     *              )
     *          ),
     *          @OA\Parameter(
     *               name="page",
     *               description="Page number",
     *               required=false,
     *               in="path",
     *               @OA\Schema(
     *                   type="integer"
     *               )
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
    public function index()
    {
        $receiptChannel = ReceiptChannel::where('status_id', 1)
            ->paginate(10);
        return (ReceiptChannelResource::collection($receiptChannel))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }
}
