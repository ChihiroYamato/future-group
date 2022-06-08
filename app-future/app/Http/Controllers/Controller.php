<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *  title="Future-task API",
 *  version="1.0.0",
 *  description="List of api methods for future task by Swagger",
 *  contact={
 *      "email": "alexan9610@gmail.com"
 *  }
 * ),
 * )
 * @OA\Server(
 *  url=L5_SWAGGER_CONST_HOST,
 *  description="API to Future-task",
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
