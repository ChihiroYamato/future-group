<?php

use App\Http\Controllers\NotebookController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/auth', [AuthController::class, 'auth']);

Route::apiResource('/notebooks', NotebookController::class)->except(['index', 'show'])->middleware('auth:sanctum');
Route::apiResource('/notebooks', NotebookController::class)->only(['index', 'show']);
