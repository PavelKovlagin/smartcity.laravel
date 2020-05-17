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

Route::middleware('auth:api')->get('/user', "UserController@apiSelectUser");

Route::get('/getOauthClient', 'UserController@apiGetClientAuthentication');

Route::post('/sendCode', 'UserController@apiSendCode');

Route::post("/passwordChange", "UserController@apiPasswordChange");

Route::middleware("auth:api")->post('/addComment', 'CommentController@apiAddComment');

Route::middleware("auth:api")->post('/deleteComment', 'CommentController@apiDeleteComment');

Route::middleware("auth:api")->post('/deleteEventImage', 'EventImageController@apideleteEventImage');

Route::middleware('auth:api')->post('/addEvent', 'EventController@apiAddEvent');

Route::middleware('auth:api')->post('/updateEvent', 'EventController@apiUpdateEvent');

Route::middleware('auth:api')->post('/updateUser', 'UserController@apiUpdateUser');

Route::post('/register', 'Auth\RegisterController@apiRegister');

Route::get('/events', 'EventController@apiSelectEvents');

Route::get('/eventComments', 'CommentController@apiSelectComments');

Route::get("/event", 'EventController@apiSelectEvent');

Route::get('/statuses', 'StatusController@apiSelectStatuses');

Route::get('/categories', 'CategoryController@apiSelectCategories');
