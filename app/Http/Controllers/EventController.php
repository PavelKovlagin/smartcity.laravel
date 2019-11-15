<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use App;

class EventController extends Controller
{
    public function showEvents(){
        //$events = App\Event::paginate(10);
        //$events = DB::select('SELECT events.id as event_id, users.id as user_id FROM events, users WHERE user_id = users.id');
        $events = DB::table('events')
        ->join('users', 'user_id', '=', 'users.id')
        ->select(
            'events.id as event_id', 
            'nameEvent', 
            'eventDescription',
            'latitude',
            'longitude', 
            'events.date as event_date',

            'users.id as user_id',
            'email')
        ->paginate(10);
        //return dd($events);
        return view('events.events', [
            'title' => 'Все события',
            'events' => $events
        ]);
    }

    public function showEvent($id){
        $event = App\Event::find($id);
        $comments = DB::table('comments')
        ->join('events', 'comments.event_id', '=', 'events.id')
        ->join('users', 'comments.user_id', '=', 'users.id')
        ->select(
            'email',
            'text',
            'dateTime'
        )
        ->get();
        return view('events.event', [
            'event' => $event,
            'comments' => $comments
        ]);
    }

    public function addEvent(Request $request){
        
            $event = new \App\Event;
            $event->user_id = Auth::user() -> id;
            $event->nameEvent = $request->nameEvent;
            $event->eventDescription = $request->eventDescription;
            $event->longitude = $request->longitude;
            $event->latitude = $request->latitude;
            $event->status = 1;
            $event->date = Carbon::now();
            $event->save();
    
            return redirect("/events");
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
