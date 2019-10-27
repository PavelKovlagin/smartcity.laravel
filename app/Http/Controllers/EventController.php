<?php

namespace App\Http\Controllers;
use DB;

class EventController extends Controller
{
    public function showEvents(){
        $events = DB::table('events')->get();
        $name = "YourNameHere";
        return view('events.events', [
            'name' => $name,
            'events' => $events
        ]);
    }

    public function showEvent($id){
        $event = DB::table('events')->find($id);
        $name = "YourNameHere";
        return view('events.event', [
            'name' => $name,
            'event' => $event
        ]);
    }
}
