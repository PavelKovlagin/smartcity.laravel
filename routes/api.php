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

Route::get('/events', 'EventController@apiSelectEvents');

Route::get('/eventComments', 'CommentController@apiSelectComments');

Route::get("/event", 'EventController@apiSelectEvent');

Route::get('/statuses', 'StatusController@apiSelectStatuses');
