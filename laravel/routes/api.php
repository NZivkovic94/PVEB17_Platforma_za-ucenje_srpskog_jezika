<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//This is just a test, should be deleted later
Route::get('/test', function() {
    return response() -> json([
        'user' => [
            'first_name' => 'Djordje',
            'last_name'  => 'Pantelic'
        ]
    ]);
});
