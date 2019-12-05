<?php

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

use Illuminate\Http\Request;
use Carbon\Carbon;

Route::get('/', function(){
        return view('welcome');
});

Route::get('/users', 'UserController@showUsers');

Route::get('/users/user/{user_id}', 'UserController@showUser');

Route::post('/deleteUser', 'UserController@deleteUser');

Route::post('/updateUser', 'UserController@updateUser');

Route::get('/statuses', 'StatusController@showStatuses');

Route::get('/statuses/addStatus', function(){
        return view('statuses.addStatus');
});

Route::post('/addStatus', 'StatusController@addStatus');

Route::get('/events', 'EventController@showEvents');

Route::get('/events/addEvent', function(){
        return view('events.addEvent');
});

Route::post('/updateEvent', 'EventController@updateEvent');

Route::get("/events/myevents", 'EventController@showUserEvents');

Route::get('/events/{id}', 'EventController@showEvent');

Route::post('/addEvent', 'EventController@addEvent');

Route::get('/deleteEvent/{event_id}', 'EventController@deleteEvent');

Route::get('/events/considersEvents', 'EventController@showConsidersEvents');

Route::post('/addComment', 'CommentController@addComment');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
