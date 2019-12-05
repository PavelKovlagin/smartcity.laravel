<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
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

    public function showEvents(){
        //$events = App\Event::paginate(10);
        //$events = DB::select('SELECT events.id as event_id, users.id as user_id FROM events, users WHERE user_id = users.id');
        if ((Auth::check()) and (Auth::user() -> role == 'admin')) {
            $events = App\Event::selectEvents()->paginate(10);   
        } else {
            $events = App\Event::selectVisibilityEvents()->paginate(10);
        }
        //return dd($events);
        return view('events.events', [
            'title' => 'Все события',
            'events' => $events,       
        ]);
    }

    public function showEvent($id){
        $event = App\Event::selectEvent($id)->get();
        $comments = App\Comment::selectCommentsFromEvent($id)->get();
        $statuses = App\Status::selectStatuses();
        //dd($event);
        return view('events.event', [
            'event' => $event[0],
            'comments' => $comments,
            'statuses' => $statuses
        ]);
    }

    public function showUserEvents() {
        if(Auth::check()) {
            $events = App\Event::selectUserEvents(Auth::user() -> id)->paginate(10);
            return view ('events.events', [
                'title' => 'Мои события',
                'events' => $events
            ]);
        } else {
            return redirect("/login");
        }
    }

    public function addEvent(Request $request){
        if (Auth::check()){
            $user_id = Auth::user() -> id;
            App\Event::insertEvent($user_id, $request);
            return redirect("/events");
        } else {
            return ("/");
        }
    }

    public function updateEvent(Request $request){
        if ((Auth::check() and (Auth::user() -> role = "admin"))){
            $event_id = $request->event_id;
            $status_id = $request->status_id;
            App\Event::updateStatus($event_id, $status_id);
            //return dd($status_id);
            return redirect ("/events/$event_id");
        } else {
            return "У вас недостаточно прав";
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
