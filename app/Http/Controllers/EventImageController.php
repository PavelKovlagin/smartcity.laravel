<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage; 
use Illuminate\Http\Request;
use App;

class EventImageController extends Controller
{
    //удаление изображений события
    public function deleteEventImages(Request $request){
        if ($request->event_images_id == null) return "Images null";
        $images = App\EventImage::selectImages($request->event_images_id)->get();
        $authUser = App\User::selectAuthUser();
        if ($authUser == false) return "User not authorized";
        foreach ($images as $image) {
            if (($authUser->levelRights > $image->user_levelRights) OR ($authUser->user_id == $image->user_id)) {
                App\EventImage::destroy($image->event_image_id);
            }            
        }
        return "Images deleted";
    }

    //web удаления изображений события
    public function webDeleteEventImages(Request $request){
        $response = $this->deleteEventImages($request);
        switch ($response) {
            case "User not authorized":
                return back()->with(["error" => "Пользователь не авторизован"]);
            break;
            case "Images deleted":
                return back()->with(["error" => "Изображения удалены"]);
            break;
            case "Images null":
                return back()->with(["error" => "Изображения не найдены"]);
            break;
        }
    }

    //api удаления изображений события
    public function apiDeleteEventImages(Request $request){
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