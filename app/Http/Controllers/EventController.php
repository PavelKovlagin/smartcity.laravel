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
    public function apiSelectEvents(Request $request){  
        $validator = Validator::make($request->all(), [
            "dateChange" => "required|date",
        ]);
        if ($validator->fails()) return $this->sendError([], "Validation failed", 418);
        $events = App\Event::selectEventsDateChange($request->dateChange)->get();
        foreach ($events as $event) {

            if ($event->visibilityForUser == 0) {
                $event->eventName = "";
                $event->eventDescription = "";
            }
        }
        return $this->sendResponse($events, count($events));
    }
    //возвращает информацию о событии, комментарии и изображения для этого события в формате json
    public function apiSelectEvent() {
        if (!request()->has('event_id')) return $this->sendError([], "No event_id", 418);
        $event = App\Event::selectEvent(request('event_id'));
        if ($event == null) return $this->sendError([], "Not found event", 418);
        return $this->sendResponse($event, $event->eventName);
    }
    //Добавление события. Параметры: $request – параметры POST запроса
    public function addEvent(Request $request) {
        $validator = Validator::make($request->all(), [
            "eventName" => "required",
            "eventDescription" => "required",
            "longitude" => "required|numeric",
            "latitude" => "required|numeric",
            "category_id" => "required"
        ]);
        if ($validator->fails()) return array("response" => "Validation failed", "validator" => $validator->errors());
        $authUser = App\User::selectAuthUser();
        if ($authUser == false) return array("response" => "User not authorized");
        if ($authUser->blocked <> false) return array("response" => "User blocked", "dateBlock" => $authUser->blockDate);
        $event_id = App\Event::insertEvent($authUser->user_id, $request);
        if ($event_id > 0) 
        {
            App\EventImage::insertEventImages($request->images, $event_id, $authUser->user_id);
            return array("response" => "Event added");  
        } else {
            return array("response" => "Event not added");
        }
    }
    public static function qwerty(){
        $authUser = App\User::selectAuthUser();
        if ($authUser == false) return "123";
        $count = App\Event::getNotViewedEventCount($authUser->user_id);
        if ($count <= 0) return "";
        return "+".$count; 
    }

    public function changeEventViewed(Request $request) {
        $event = App\Event::selectEvent($request->event_id);
        $authUser = App\User::selectAuthUser();
        if ($event == null) return "Событие не найдено";
        if ($event->user_id <> $authUser->user_id) return "Это не ваше событие";
        if ($event->viewed == 0) {
            App\Event::changeEventViewed($request->event_id, 1);
        } else {
            App\Event::changeEventViewed($request->event_id, 0);
        }
        return back();
    }
    //добавление события в WEB
    public function webAddEvent(Request $request){        
        $response = $this->addEvent($request);
        switch ($response["response"]) {
            case "Validation failed":
                return back()->with(["message" => "Валидация не пройдена: " . $response["validator"]]);
            break;
            case "User not authorized":
                return back()->with(["message" => "Пользователь не авторизован"]);
            break;
            case "User blocked":
                return back()->with(["message" => "Пользователь заблокирован до " . $response["dateBlock"]]);
            break;
            case "Event not added":
                return back()->with(["message" => "Событие не добавлено"]);
            break;
            case "Event added":
                return redirect("/events")->with(["message" => "Событе добавлено"]);
            break;            
        }
    }
    //API добавления события
    public function apiAddEvent(Request $request) {

        $response = $this->addEvent($request);
        switch ($response["response"]) {
            case "Validation failed":
                return $this->sendError($response["validator"], "Validation failed", 418);
            break;
            case "User not authorized":
                return $this->sendError([], "User not authorized", 418);
            break;
            case "User blocked":
                return $this->sendError([], "User blocked " . $response["dateBlock"], 418);
            break;
            case "Event not added":
                return $this->sendError([], "Event not added", 418);
            break;
            case "Event added":
                return $this->sendResponse($request->all(), "Event added");
            break;            
        }
    }

    //Обновление события. Параметры: $request – параметры POST запроса
    public function updateEvent(Request $request) {
        $validator = Validator::make($request->all(), [
            'event_id' => "required",
            "eventName" => "required",
            "eventDescription" => "required",
            "longitude" => "required|numeric",
            "latitude" => "required|numeric",
            "category_id" => "required"
        ]);
        if ($validator->fails()) {
            return array("response" => "Validation failed", "validator" => $validator->errors());
        }
        $authUser = App\User::selectAuthUser();
        $event = App\Event::selectEvent($request->event_id);        
        if ($event == null) 
            return array("response" => "Event not found");
        $user = App\User::selectUser($event->user_id);
        if (!$authUser) {
            return array("response" => "User not authorized");
        }
        if (($authUser->user_id <> $event->user_id) AND ($authUser->levelRights < $user->levelRights)) {
            return array("response" => "It's not your event. Go away");
        }
        if (($event->status_id <> 1) AND ($authUser->levelRights < 2)) {
            return array("response" => "Event have other status. Too late");
        }
        $event_id = App\Event::updateEvent($request);
        if ($event_id = 0) {     
            return array("response" => "Event not update");       
        }
        App\EventImage::insertEventImages($request->images, $request->event_id, $authUser->user_id);
        return array("response" => "Event update");
    }
    
    //API обновления события
    public function apiUpdateEvent(Request $request){
        $response = $this->updateEvent($request);
        switch ($response["response"]) {
            case "Validation failed":
                return $this->sendError($response["validator"], $response["response"], 418);
            break;
            case "Event not found":
                return $this->sendError([], $response["response"], 418);
            break;
            case "User not authorized":
                return $this->sendError([], $response["response"], 418);
            break;
            case "It's not your event. Go away":
                return $this->sendError([], $response["response"], 418);
            break;
            case "Event have other status. Too late":
                return $this->sendError($request->all(), $response["response"], 418);
            break; 
            case "Event not update":
                return $this->sendError([], $response["response"], 418);
            break;  
            case "Event update":
                return $this->sendResponse($request->all(), $response["response"]);
            break;           
        }
    }
    //Обновления события в Web
    public function webUpdateEvent(Request $request){
        $response = $this->updateEvent($request);
        switch ($response["response"]) {
            case "Validation failed":
                return back()->with(["message" => "Валидация не пройдена: " . $response["validator"]]);
            break;
            case "Event not found":
                return redirect("/events")->with(["message" => "Событие не найдено"]);
            break;
            case "User not authorized":
                return back()->with(["message" => "Пользователь не авторизован"]);
            break;
            case "It's not your event. Go away":
                return back()->with(["message" => "Это не ваше событие"]);
            break;
            case "Event have other status. Too late":
                return back()->with(["message" => "Вы не можете обновить событие, так как оно прошло модерацию"]);
            break; 
            case "Event not update":
                return back()->with(["message" => "Событие не обновлено"]);
            break;  
            case "Event update":
                return back()->with(["message" => "Событие обновлено"]);
            break;           
        }
    }
    //Открытие представления events с информацией о событиях
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
    //Открытие представления event с информацией о событии. Параметры: $id – идентификатор события
    public function showEvent($id){
        $authUser = App\User::selectAuthUser();
        $event = App\Event::selectEvent($id);
        if ($event == null) return redirect("/events")->with(["message" => "Событие не найдено"]);
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
    //Обновление статуса события. Параметры: $request – параметры POST запроса
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