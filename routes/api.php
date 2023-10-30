<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Api\CollectionController;

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


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['middleware' => 'auth:sanctum', 'prefix' => '/collections'], function ()
{

    Route::post('/add', [CollectionController::class, 'add']);
    Route::post('/update/{collection}', [CollectionController::class, 'update']);
    Route::post('/video/update', [CollectionController::class, 'videoUpdate']);

});

/**
 * Ruta por defecto cuando no se encuentra una petición.
 */
Route::fallback(function(){
    return response()->json(['message' => 'Page Not Found'], 404);
});
