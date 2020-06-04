<?php

namespace App\Http\Controllers;

use App\CommentImages;
use Illuminate\Http\Request;
use App;

class CommentImageController extends Controller
{
    //Удаление изображений комментари. Параметры: $request – параметры POST запроса
    public function deleteCommentImages(Request $request){        
        if ($request->comment_images_id == null) return array("response" =>"Images null");
        $images = App\CommentImage::selectImages($request->comment_images_id)->get();
        $authUser = App\User::selectAuthUser();
        if ($authUser == false) return array("response" => "User not authorized");    
        $deletedImages = 0;
        foreach ($images as $image) {
            if (($authUser->levelRights > $image->user_levelRights)
                OR ($authUser->user_id == $image->user_id)) {
                App\CommentImage::destroy($image->image_id);
                $deletedImages++;
            }
        }       
        return array("response" => "Images deleted", "Deleted images" => $deletedImages);
    }        

    public function webDeleteCommentImages(Request $request){
        $response = $this->deleteCommentImages($request);
        switch ($response["response"]) {
            case "User not authorized":
                return back()->with(["message" => "Пользователь не авторизован"]);
            break;
            case "Images null":
                return back()->with(["message" => "Изображения не выбраны"]);
            case "Images deleted":
                return back()->with(["message" => " Изображений удалено: " . $response["Deleted images"]]);
            break;
        }
    } 

    //api удаления изображения
    public function apiDeleteCommentImages(Request $request){        
        $response = $this->deleteCommentImages($request);
        switch ($response["response"]) {
            case "User not authorized":
                return $this->sendError([], $response, 418);
            break;
            case "Images null":
                return $this->sendError([], $response, 418);
            break;
            case "Images deleted":
                return $this->sendResponse($request->all(), $response);
            break;
        }      
    }
}
