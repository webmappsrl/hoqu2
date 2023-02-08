<?php

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
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

/**
 * Only users with special token ability can register users
 */
Route::post('hoqu/register', [ApiController::class, 'register'])->middleware(['auth:sanctum', 'abilities:register-users']);


/**
 * Where the caller ask to hoqu to execute a job
 *
 * POST /api/hoqu/store
 */
Route::post('hoqu/store', [ApiController::class, 'store'])->middleware('auth:sanctum');


/**
 * Where the processor send to hoqu the job output
 *
 * POST /api/hoqu/done
 */
Route::post('hoqu/done', [ApiController::class, 'done'])->middleware('auth:sanctum');

/**
 * Check if authtentication works from processors/callers
 */
Route::get('hoqu/ping', function () {
  return 'pong';
})->middleware('auth:sanctum');

/**
 * Authentication with username and password
 * release a special token with register-users ability
 */
Route::post('hoqu/register-login', [ApiController::class, 'registerLogin']);
