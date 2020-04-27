<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use App;

class EventController extends Controller
{

    public function apiSelectEvents(){
        if (!request()->has('dateChange')) return "false";
        $dateChange = request('dateChange');
        if (preg_match("/\d\d\d\d-\d\d-\d\d \d\d:\d\d:\d\d/", $dateChange)) {
        $events = App\Event::selectEventsDateChange($dateChange)->get();
        foreach ($events as $event) {
            if ($event->visibilityForUser == 0) {
                $event->eventName = "";
                $event->eventDescription = "";
            }
        }
        return $events;
        } else {
            return "false";  
        }
    }

    public function apiSelectEvent() {
        if (!request()->has('event_id')) return "false";
        $event_id = request('event_id');
        $event = App\Event::selectEvent($event_id)->get();
        return $event;
    }

    public function apiAddEvent(Request $request) {
        $validator = Validator::make($request->all(), [
            "eventName" => "required",
            "eventDescription" => "required",
            "longitude" => "required|numeric",
            "latitude" => "required|numeric"
        ]);
        if ($validator->fails()) {
            return $this->sendError('Error adding event.', $validator->errors());
        }
        App\Event::insertEvent(\Auth::id(), $request);
        return $this->sendResponse($request->all(), 'Event added.');
    }
    
    public function showEvents(){
        $authUser = App\User::selectAuthUser();
        if (request()->has('user_id')){
            $user = App\User::find(request('user_id'));  
            if (empty($user)){
                $title = 'Все события';
            } else {
                $title = "События пользователя " . $user->email;
            }
            if (Auth::check() AND Auth::user() -> id == request('user_id')){
                $events = App\Event::selectUserEvents(request('user_id'));
                $statuses = App\Status::selectStatuses();
            } else {
                if (($authUser <> false) AND ($authUser->levelRights > 1)) {
                    $events = App\Event::selectEvents();
                    $statuses = App\Status::selectStatuses();   
                } else {
                    $events = App\Event::selectVisibilityEvents();
                    $statuses = App\Status::selectVisibilityStatuses();
                }
            }            
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
        $events = $events->paginate(10);

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

    public function showEvent($id){
        $authUser = App\User::selectAuthUser();
        $event = App\Event::selectEvent($id);
        $user = App\User::selectUser($event->user_id);
        $comments = App\Comment::selectCommentsFromEvent($id)->get();
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
    public function addEvent(Request $request){
        $authUser = App\User::selectAuthUser();
        if ($authUser<>false AND $authUser->blocked == false){
            $user_id = Auth::user() -> id;
            App\Event::insertEvent($user_id, $request);
            return redirect("/events");
        } else {
            return redirect("/events");
        }
    }

    public function updateEvent(Request $request){
        if ((Auth::check() and (Auth::user() -> role = "admin"))){
            App\Event::updateEvent($request);
            return redirect ("/events/$request->event_id");
        } else {
            return "У вас недостаточно прав";
        }
    }

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

    public function deleteEvent($event_id){

        if ((Auth::check()) and (Auth::user() -> role = "admin")) {
            App\Event::destroy($event_id);
            return redirect("/events");
        } else {
            return redirect("/");
        }
    }
}
