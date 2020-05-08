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
        ->join('categories', 'category_id', '=', 'categories.id')
        ->orderBy('dateChange', 'asc')
        ->select(
            'events.id', 
            'eventName', 
            'eventDescription',
            'latitude',
            'longitude', 
            'events.date as event_date',
            'dateChange',
            'statuses.id as status_id',
            'statusName',
            'categories.id as category_id',
            'categoryName',
            'users.id as user_id',
            'email',
            'visibilityForUser');
        return $events;
    }

    protected static function selectVisibilityEvents(){
        $events = Event::selectEvents()
        ->where('visibilityForUser', '=', 1);        
        return $events;
    }

    protected static function selectEventsDateChange($dateChange) {
        $events = Event::selectEvents()
        ->where('dateChange', '>=', $dateChange);
        return $events;
    }

    protected static function selectUserEvents($user_id) {
        $events = Event::selectEvents()
        ->where('users.id', '=', $user_id);
        return $events;
    }

    public static function selectVisibilityUserEvents($user_id) {
        $events = Event::selectEvents()
        ->where('users.id', '=', $user_id)
        ->where('visibilityForUser', '=', 1);
        return $events;
    }

    protected static function selectEvent($event_id) {
        $event = Event::selectEvents()
        ->where('events.id', '=', $event_id)
        ->first();
        return $event;
    }  

    protected static function changeCategory($currentCategory_id){
        $firstCategory_id = Category::selectCategories()->get()[0]->id;
        DB::table('events')
        ->where('category_id', '=', $currentCategory_id)
        ->update(array('category_id' => $firstCategory_id,
                        'dateChange' => Carbon::now()));
    }

    protected static function changeStatus($currentStatus_id){
        $firstStatus_id = Status::selectStatuses()->get()[0]->id;
        DB::table('events')
        ->where('status_id', '=', $currentStatus_id)
        ->update(array('status_id' => $firstStatus_id,
                        'dateChange' => Carbon::now()));
    }

    protected static function updateEvent($request) {
        DB::table('events')
        ->where('events.id', '=', $request->event_id)
        ->update(array('eventName' => $request->eventName, 
                        'eventDescription' => $request->eventDescription,
                        'longitude' => $request->longitude,
                        'latitude'=>$request->latitude,
                        'category_id' => $request->category_id,
                        'dateChange' => Carbon::now()));
    }

    protected static function updateEventStatus($event_id, $status_id) {
        DB::table('events')
        ->where('events.id', '=', $event_id)
        ->update(array('status_id' => $status_id, 
                        'dateChange' => Carbon::now()));
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
            return $event->id;
    }
}
