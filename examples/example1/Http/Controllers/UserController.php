<?php

namespace App\Http\Controllers;

use App\Http\Resources\User\GetTokenResource;
use App\Http\Resources\User\GetUserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/get-auth-user",
     *      tags={"Пользователь"},
     *      summary="Получение авторизованного пользователя",
     *      description="Получение авторизованного пользователя",
     *          @OA\Response(
     *              response=200,
     *              description="Successful operation",
     *              @OA\JsonContent(ref="#/components/schemas/GetUserResource")
     *          ),
     *          @OA\Response(
     *              response=401,
     *              description="Unauthenticated",
     *          ),
     *          @OA\Response(
     *              response=419,
     *              description="CSRF token mismatch"
     *          )
     *     )
     */
    public function getUser(Request $request): GetUserResource
    {
        return new GetUserResource($request->user());
    }

    public function notifications(Request $request)
    {
        $notificationSettings = $request->notification_settings;
        $user = Auth::user();
        $user->notification_settings = $notificationSettings;
        $user->save();
    }
}
