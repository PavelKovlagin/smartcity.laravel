<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use DB;
use App;

class EventController extends Controller
{
    //возвращение запроса, после определенной даты, в формате json
    public function apiSelectEvents(){   
        $dateChange = request('dateChange');     
        if (preg_match("/\d\d\d\d-\d\d-\d\d \d\d:\d\d:\d\d/", $dateChange)) {
            $dateChange = request('dateChange');
            $events = App\Event::selectEventsDateChange($dateChange)->get();
            foreach ($events as $event) {
                //if ($authUser->user_id <> $event->user_id)
                if ($event->visibilityForUser == 0) {
                    $event->eventName = "";
                    $event->eventDescription = "";
                }
            }
            return $this->sendResponse($events, count($events));
        } else {
            return $this->sendError('Error load', 'Error load', 418);;  
        }
    }
    //возвращает информацию о событии, комментарии и изображения для этого события в формате json
    public function apiSelectEvent() {
        if (!request()->has('event_id')) return $this->sendError([], "No event_id", 418);
        $event = App\Event::selectEvent(request('event_id'));
        if ($event == null) return $this->sendError([], "Not found event", 418);
        $comments = \App\Comment::selectCommentsFromEvent($event->id);
        return $this->sendResponse(['event' => $event, 'comments' => $comments], $event->eventName);
    }
    //api добавления события
    public function apiAddEvent(Request $request) {
        $validator = Validator::make($request->all(), [
            "eventName" => "required",
            "eventDescription" => "required",
            "longitude" => "required|numeric",
            "latitude" => "required|numeric",
            "category_id" => "required"
        ]);
        if ($validator->fails()) {
            return $this->sendError('Error update event. Validation failed', $validator->errors(), 418);
        }
        $authUser = App\User::selectAuthUser();
        if ($authUser<>false AND $authUser->blocked == false) {
            if ($event_id = App\Event::insertEvent(Auth::id(), $request) <> false) {
                App\EventImage::insertEventImages($request->images, $event_id, Auth::id()); 
                return $this->sendResponse($request->all(), 'Event added.');
            } else {
                return $this->sendError($request->all(), 'Event not added.', 418);
            }            
        } else {
            return $this->sendError($request->all(), 'User blocked.', 418);
        }
    }
    //api обновления события
    public function apiUpdateEvent(Request $request) {
        $validator = Validator::make($request->all(), [
            'event_id' => "required",
            "eventName" => "required",
            "eventDescription" => "required",
            "longitude" => "required|numeric",
            "latitude" => "required|numeric",
            "category_id" => "required"
        ]);
        if ($validator->fails()) {
            return $this->sendError('Error adding event.', $validator->errors(), 418);
        }
        $authUser = App\User::selectAuthUser();
        $event = App\Event::selectEvent($request->event_id);
        if (!$authUser) {
            return $this->sendError($request->all(), 'Authorization failed.', 418);
        }
        if ($authUser->user_id <> $event->user_id) {
            return $this->sendError($request->all(), "It's not your event. Go away", 418);
        }
        if($event->status_id <> 1) {
            return $this->sendError($request->all(), 'Event have other status. Too late', 418);
        }
        if (!App\Event::updateEvent($request)) {
            return $this->sendError($request->all(), 'Event not update.', 418);
        }
        return $this->sendResponse($request->all(), 'Event update.');
    }
    //добавление события и перенаправления на страницу со списком всех событий
    public function addEvent(Request $request){
        $authUser = App\User::selectAuthUser();
        if ($authUser<>false AND $authUser->blocked == false){
            $event_id = App\Event::insertEvent($authUser->user_id, $request);
            App\EventImage::insertEventImages($request->images, $event_id, $authUser->user_id);            
            return redirect("/events");
        } else {
            return redirect("/events");
        }
    }
    //обновление события и перенаправление на страницу события 
    public function updateEvent(Request $request){
        $authUser = App\User::selectAuthUser();
        $event = App\Event::selectEvent($request->event_id);
        $user = App\User::selectUser($event->user_id);
        if (($authUser<>false) 
            AND (($authUser->levelRights > $user->levelRights)
                OR (($authUser->user_id == $user->user_id) AND ($event->status_id == 1 OR $authUser->levelRights > 1)))) {
            App\EventImage::insertEventImages($request->images, $request->event_id, $authUser->user_id);   
            App\Event::updateEvent($request);
            return redirect ("/events/$request->event_id");
        } else {
            return redirect ("/events/$request->event_id");;
        }
    }
    //передача данных и открытие страницы со списком событий
    public function showEvents(){
        $authUser = App\User::selectAuthUser();
        if (request()->has('user_id') 
            AND (!empty($user = App\User::find(request('user_id'))))){
            $title = "События пользователя " . $user->email;
            if (($authUser <> false) AND ($authUser->user_id == request('user_id') OR ($authUser->levelRights > 1))){
                $events = App\Event::selectUserEvents(request('user_id'));
                $statuses = App\Status::selectStatuses();
            } else {
                    $events = App\Event::selectVisibilityUserEvents($user->id);
                    $statuses = App\Status::selectVisibilityStatuses();}                      
        } else {
            if (($authUser <> false) AND ($authUser->levelRights > 1)) {
                $events = App\Event::selectEvents();
                $statuses = App\Status::selectStatuses();   
            } else {
                $events = App\Event::selectVisibilityEvents();
                $statuses = App\Status::selectVisibilityStatuses();
            }
            $title = "Все события";
        }
        $categories = App\Category::selectCategories();
        if ((request()->has('status_id')) AND ((request('status_id') <> 0))){
            $events = $events -> where('status_id', '=', request('status_id'));
        }
        if ((request()->has('category_id')) AND ((request('category_id') <> 0))){
            $events = $events -> where('category_id', '=', request('category_id'));
        }
        $events = $events->get();

        return view('events.events', [
            'user_id' => request('user_id'),
            'status_id' => request('status_id'),
            'category_id' => request('category_id'),
            'statuses' => $statuses->get(),
            'categories' => $categories->get(),
            'title' => $title,
            'events' => $events,       
        ]);
    }
    //передача данных и открытие страницы определенного события
    public function showEvent($id){
        $authUser = App\User::selectAuthUser();
        $event = App\Event::selectEvent($id);
        if($event == null) return redirect("/events")->with(["message" => "Событие не найдено"]);
        $user = App\User::selectUser($event->user_id);
        $comments = App\Comment::selectCommentsFromEvent($id);
        $statuses = App\Status::selectStatuses();
        $categories = App\Category::selectCategories();
        return view('events.event', [
            'user' => $user,
            'authUser' => $authUser,
            'event' => $event,
            'comments' => $comments,
            'statuses' => $statuses->get(),
            'categories' => $categories->get()]);
    }
    //обновление статуса события
    public function updateEventStatus(Request $request){
        $authUser = App\User::selectAuthUser();
        $user = App\User::selectUser($request->user_id);        
        if (($authUser<>false)
            AND (($authUser->levelRights > $user->levelRights)
            OR ($authUser->user_id == $request->user_id))){
            App\Event::updateEventStatus($request->event_id, $request->status_id);
            return back();
        }
    }
}