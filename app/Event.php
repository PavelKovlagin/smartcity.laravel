<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use DB;

class Event extends Model
{
    protected static function selectEvents(){
        $events = DB::table('events')
        ->join('users', 'user_id', '=', 'users.id')
        ->join('statuses', 'status_id', '=', 'statuses.id')
        ->select(
            'events.id as event_id', 
            'eventName', 
            'eventDescription',
            'latitude',
            'longitude', 
            'events.date as event_date',
            'dateChange',
            'statuses.id as status_id',
            'statusName',
            'users.id as user_id',
            'email')
        ->get();
        return $events;
    }

    protected static function selectEventsPaginate(){
        $events = DB::table('events')
        ->join('users', 'user_id', '=', 'users.id')
        ->join('statuses', 'status_id', '=', 'statuses.id')
        ->select(
            'events.id as event_id', 
            'eventName', 
            'eventDescription',
            'latitude',
            'longitude', 
            'events.date as event_date',
            'dateChange',
            'statuses.id as status_id',
            'statusName',
            'users.id as user_id',
            'email')
        ->paginate(10);
        return $events;
    }

    protected static function updateStatus($event_id, $status_id) {
        DB::table('events')
        ->where('events.id', '=', $event_id)
        ->update(array('status_id' => $status_id, "dateChange" => Carbon::now()));
    }

    protected static function insertEvent($user_id, $request) {
            $event = new \App\Event;
            $event->user_id = $user_id;
            $event->eventName = $request->eventName;
            $event->eventDescription = $request->eventDescription;
            $event->longitude = $request->longitude;
            $event->latitude = $request->latitude;
            $event->status_id = 1;
            $event->date = Carbon::now();
            $event->dateChange = Carbon::now();
            $event->save();
    }
}
