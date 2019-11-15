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

Route::get('/users', 'UserController@showUsers');

Route::get('/events/addEvent', function(){
        return view('events.addEvent');
});

Route::get('/', function(){
        return view('welcome');
});

Route::get('/users/user/{user_id}', 'UserController@showUser');

Route::post('/deleteUser', 'UserController@deleteUser');

Route::get('/deleteEvent/{event_id}', 'EventController@deleteEvent');

Route::post('/addEvent', 'EventController@addEvent');

Route::post('/updateUser', 'UserController@updateUser');

Route::post('/addComment', 'CommentController@addComment');

Route::get('/events', 'EventController@showEvents');

Route::get('/events/considersEvents', 'EventController@showConsidersEvents');

Route::get('/events/{id}', 'EventController@showEvent');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
