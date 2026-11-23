<?php

use App\Http\Controllers\Api\CollectionController;
use App\Http\Controllers\Api\FallbackController;
use App\Http\Controllers\Api\UserController;
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

Route::middleware('auth:sanctum')->get('/user', [UserController::class, 'show']);

Route::group(['middleware' => 'auth:sanctum', 'prefix' => '/collections'], function () {
    Route::post('/add', [CollectionController::class, 'add']);
    Route::post('/update/{collection}', [CollectionController::class, 'update']);
    Route::post('/video/update', [CollectionController::class, 'videoUpdate']);
});

/*
 * Ruta por defecto cuando no se encuentra una petición.
 */
Route::fallback(FallbackController::class);
