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
        $events = App\Event::selectEvents();
        return $events;
    }

    public function showEvents(){
        //$events = App\Event::paginate(10);
        //$events = DB::select('SELECT events.id as event_id, users.id as user_id FROM events, users WHERE user_id = users.id');
        $events = App\Event::selectEventsPaginate();   
        //return dd($events);
        return view('events.events', [
            'title' => 'Все события',
            'events' => $events,       
        ]);
    }

    public function showEvent($id){
        $event = App\Event::find($id);
        $comments = App\Comment::selectCommentsFromEvent($id);
        $statuses = App\Status::selectStatuses();
        return view('events.event', [
            'event' => $event,
            'comments' => $comments,
            'statuses' => $statuses
        ]);
    }

    public function addEvent(Request $request){
        if (Auth::check()){
            $user_id = Auth::user() -> id;
            App\Event::insertEvent($user_id, $request);
            return redirect("/events");
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
            return redirect("/");
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
