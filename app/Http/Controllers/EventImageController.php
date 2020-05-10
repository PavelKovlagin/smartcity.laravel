<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage; 

use Illuminate\Http\Request;
use App;

class EventImageController extends Controller
{
    public function deleteEventImage(Request $request){
        $eventImage = App\EventImage::selectEventImage($request->event_id, $request->image_id);
        if ($eventImage == null) {
            return back();
        } else {
            $authUser = App\User::selectAuthUser();
            $user = App\User::selectUser($eventImage->user_id);
            if ($authUser <> false
                AND (($authUser->levelRights > $user->levelRights)
                    OR ($authUser->user_id == $user->user_id)))
            App\EventImage::destroy($request->image_id);
            return back();
        }        
    }
}