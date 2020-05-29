<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage; 
use Illuminate\Http\Request;
use App;

class EventImageController extends Controller
{
    //Удаление изображений событий. Параметры: $request – параметры POST запроса
    public function deleteEventImages(Request $request){        
        if ($request->event_images_id == null) return array("response" => "Images null");
        $images = App\EventImage::selectImages($request->event_images_id)->get();
        $authUser = App\User::selectAuthUser();
        if ($authUser == false) return array("response" => "User not authorized");
        $deletedImages = 0;
        foreach ($images as $image) {
            if (($authUser->levelRights > $image->user_levelRights) OR ($authUser->user_id == $image->user_id)) {
                App\EventImage::destroy($image->event_image_id);
                $deletedImages++;
            }            
        }
        return array("response" => "Images deleted", "Deleted images" => $deletedImages);
    }

    //web удаления изображений события
    public function webDeleteEventImages(Request $request){
        $response = $this->deleteEventImages($request);
        switch ($response["response"]) {
            case "User not authorized":
                return back()->with(["message" => "Пользователь не авторизован"]);
            break;
            case "Images deleted":
                return back()->with(["message" => "Изображений удалено: " . $response["Deleted images"]]);
            break;
            case "Images null":
                return back()->with(["message" => "Изображения не найдены"]);
            break;
        }
    }

    //api удаления изображений события
    public function apiDeleteEventImages(Request $request){
        $response = $this->deleteEventImages($request);
        switch ($response["response"]) {
            case "User not authorized":
                return $this->sendError([], $response["response"], 418);
            break;
            case "Images deleted":
                return $this->sendResponse($request->all(), $response["response"] . ": " . $response["Deleted images"]);
            break;
            case "Images null":
                return $this->sendError([], $response["response"], 418);
            break;
        }       
    }
}