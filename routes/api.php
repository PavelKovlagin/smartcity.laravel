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

Route::get('/getOauthClient', 'UserController@apiGetClientAuthentication');

Route::middleware("auth:api")->post('/addComment', 'CommentController@apiAddComment');

Route::middleware('auth:api')->post('/addEvent', 'EventController@apiAddEvent');

Route::middleware('auth:api')->post('/updateEvent', 'EventController@apiUpdateEvent');

Route::post('/register', 'Auth\RegisterController@apiRegister');

Route::get('/events', 'EventController@apiSelectEvents');

Route::get('/eventComments', 'CommentController@apiSelectComments');

Route::get("/event", 'EventController@apiSelectEvent');

Route::get('/statuses', 'StatusController@apiSelectStatuses');

Route::get('/categories', 'CategoryController@apiSelectCategories');
