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
        $images = App\Image::selectImages();
        return view('welcome');
});

//работа с пользователями

Route::get('/resetPassword', function(){
        return view('auth.passwords.reset');
});

Route::post('/sendCode', 'UserController@sendCode');

Route::get('/passwordChange', function(){
        return view('auth.passwords.password_change');
});

Route::post('/passwordChange', 'UserController@passwordChange');

Route::get('/users', 'UserController@showUsers');

Route::get('/users/user/{user_id}', 'UserController@showUser');

Route::post("/updateUser", "UserController@updateUser");

Route::post('/blockedUser', 'UserController@blockedUser');

Route::post('/updateRole', 'UserController@updateRole');

//работа со статусами
Route::get('/statuses', 'StatusController@showStatuses');

Route::get('/statuses/addStatus', function(){
        return view('statuses.addStatus');
});

Route::get('/statuses/{id}', 'StatusController@showStatus');

Route::post('/updateStatus', "StatusController@updateStatus");

Route::post('/deleteStatus', 'StatusController@deleteStatus');

Route::post('/addStatus', 'StatusController@addStatus');

//работа с категориями
Route::get('/categories', 'CategoryController@showCategories');

Route::get('/categories/addCategory', function(){
        return view('categories.addCategory');
});

Route::get('/categories/{id}', 'CategoryController@showCategory');

Route::post('/updateCategory', "CategoryController@updateCategory");

Route::post('/deleteCategory', 'CategoryController@deleteCategory');

Route::post('/addCategory', 'CategoryController@addCategory');

//работа с событиями
Route::get('/events', 'EventController@showEvents');

Route::get('/events/addEvent', function(){
        return view('events.addEvent', 
        ['authUser' => App\User::selectAuthUser(),
        'categories' => App\Category::selectCategories()->get()]);
});

Route::post('/updateEvent', 'EventController@updateEvent');

Route::post('/updateEventStatus', 'EventController@updateEventStatus');

Route::get('/events/{id}', 'EventController@showEvent');

Route::post('/addEvent', 'EventController@addEvent');

Route::get('/deleteEvent/{event_id}', 'EventController@deleteEvent');

Route::get('/events/considersEvents', 'EventController@showConsidersEvents');

Route::post("/deleteEventImage", "EventImageController@deleteEventImage");

//работа с комментариями

Route::post('/addComment', 'CommentController@addComment');

Route::post('/deleteComment', 'CommentController@deleteComment');

//работа с изображениями

Route::post('/uploadImage', 'ImageController@uploadImage');

Auth::routes(['register' => true,
        'verify' => false,
        'reset' => false]);

Route::get('/home', 'HomeController@index')->name('home');


