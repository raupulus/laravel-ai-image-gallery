<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\PageController;

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

#################           Páginas           #################

Route::group(['prefix' => 'page'], function () {
    Route::get('/{page}', [PageController::class, 'show'])->name('page.show');
});

#################           Colecciones           #################

Route::group(['prefix' => 'collections'], function () {
    Route::get('/show/{collection}', [CollectionController::class, 'show'])->name('collections.show');
    Route::get('/random', [CollectionController::class, 'random'])->name('collections.random');


    ## Acciones de administración en colecciones
    Route::group(['middleware' => 'auth'], function () {

        ## Eliminar colección.
        Route::post('/delete/{collection}', [CollectionController::class, 'delete'])->name('collection.delete');

        ## Eliminar una imagen de la colección.
        Route::post('/{collection}/image/{image}/delete', [CollectionController::class, 'deleteImage'])->name('collection.image.delete');
    });

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

if (app()->isLocal() && config('app.debug')) {
    Route::get('/tokens/create', function (Request $request) {
        $user = \App\Models\User::first();
        if (!$user) {
            abort(404, 'No user found to generate token.');
        }

        $token = $user->createToken('general');

        return ['token' => $token->plainTextToken];
    });
}

require __DIR__.'/auth.php';
