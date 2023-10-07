<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
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

