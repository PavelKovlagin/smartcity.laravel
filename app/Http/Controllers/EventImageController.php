<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage; 
use Illuminate\Http\Request;
use App;

class EventImageController extends Controller
{
    //удаление изображения
    public function deleteEventImage(Request $request){
        $eventImage = App\EventImage::selectEventImage($request->image_id);
        if ($eventImage == null) {
            return back();
        } else {
            $authUser = App\User::selectAuthUser();
            $user = App\User::selectUser($eventImage->user_id);
            if ($authUser <> false
                AND (($authUser->levelRights > $user->levelRights)
                    OR ($authUser->user_id == $user->user_id)))
            App\EventImage::destroy($request->image_id);
            return back()->with(["error" => "Изображение удалено"]);
        }        
    }
    //api удаления изображения
    public function apiDeleteEventImage(Request $request){
        $eventImage = App\EventImage::selectEventImage($request->image_id);
        if ($eventImage == null) {
            return $this->sendError($request->all(), "Image not found", 418);
        } else {
            $authUser = App\User::selectAuthUser();
            $user = App\User::selectUser($eventImage->user_id);
            if ($authUser <> false
                AND (($authUser->levelRights > $user->levelRights)
                    OR ($authUser->user_id == $user->user_id))){
                App\EventImage::destroy($request->image_id);
                return $this->sendResponse($request->all(), "Image delete");
            } else {
                return $this->sendError($request->all(), "User failed", 418); 
            }            
        }        
    }
}