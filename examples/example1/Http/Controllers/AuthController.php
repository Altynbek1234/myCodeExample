<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *      path="/api/get-token",
     *      tags={"Аутентификация"},
     *      summary="Получение токена для работы с Postman",
     *      description="Получение токена для работы с Postman",
     *          @OA\RequestBody(
     *              required=true,
     *              @OA\JsonContent(ref="#/components/schemas/GetTokenRequest")
     *          ),
     *          @OA\Response(
     *              response=200,
     *              description="Successful operation",
     *              @OA\JsonContent(ref="#/components/schemas/GetTokenResource")
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
    public function getToken(Request $request)
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $auth = Auth::user();
            $success = [];
            $success['token'] = $auth->createToken('LaravelSanctumAuth')->plainTextToken;
            $success['name'] = $auth->name;

            return json_encode($success);
        } else {
            return abort(401);
        }
    }
}
