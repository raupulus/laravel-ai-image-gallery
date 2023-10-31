<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\IndexController;
use \App\Http\Controllers\CollectionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [IndexController::class, 'index'])->name('home');

Route::group(['prefix' => 'collections'], function () {
    Route::get('/show/{collection}', [CollectionController::class, 'show'])->name('collections.show');
    Route::get('/random', [CollectionController::class, 'random'])->name('collections.random');


    // Temporal, AJAX

    Route::group(['prefix' => 'ajax'], function () {
        Route::get('/get/cards/{page?}/{search?}', [CollectionController::class, 'ajaxGetHomeCards'])
            ->name('collection.ajax.get.cards');
    });
});

Route::group(['prefix' => 'webhooks'], function () {
    Route::any('/twitter', function () {
        return response()->json([
            'success' => true
        ]);
    });
});

if (config('app.debug')) {
    Route::get('/tokens/create', function (Request $request) {
        //if (auth()->guest()) {
        //    return '<h1>NO ESTAS LOGUEADO</h1>';
        //}

        $user = \App\Models\User::find(1);

        //$token = $request->user()->createToken($request->token_name);
        $token = $user->createToken('general');

        return ['token' => $token->plainTextToken];
    });
}

