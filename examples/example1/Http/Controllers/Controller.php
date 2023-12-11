<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @SWG\Swagger(
 *   @OA\Info(
 *        version="1.0.0",
 *        title="Документация для АИС Акыйкатчы",
 *        description="",
 *        @OA\Contact(
 *            email="admin@example.com"
 *        ),
 *        @OA\License(
 *            name="Apache 2.0",
 *            url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *        )
 *   )
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

}
