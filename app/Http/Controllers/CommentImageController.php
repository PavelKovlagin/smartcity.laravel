<?php

namespace App\Http\Controllers;

use App\CommentImages;
use Illuminate\Http\Request;
use App;

class CommentImageController extends Controller
{
    //удаление изображения
    public function deleteCommentImages(Request $request){
        if ($request->comment_images_id == null) return array("response" =>"Images null");
        $comment = App\Comment::selectComment($request->comment_id);
        if ($comment == null) return array("response" => "Comment not found");
        $authUser = App\User::selectAuthUser();
        $user = App\User::selectUser($comment->user_id);
        if ($authUser == false) return array("response" => "User not authorized");    
        $deletedImages = 0;
        foreach ($request->comment_images_id as $comment_image_id) {
            if (($authUser->levelRights > $user->levelRights)
                OR ($authUser->user_id == $user->user_id)) {
                App\CommentImage::destroy($comment_image_id);
                $deletedImages++;
            }
        }       
        return array("response" => "Images deleted", "Deleted images" => $deletedImages);
    }        

    public function webDeleteCommentImages(Request $request){
        $response = $this->deleteCommentImages($request);
        switch ($response["response"]) {
            case "Comment not found":
                return back()->with(["message" => "Комментарий не найден"]);
            break;
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
    public function apiDeleteCommentImagesZz(Request $request){
        $response = $this->deleteCommentImages($request);
        switch ($response) {
            case "Comment not found":
                return $this->sendError([], $response, 418);
            break;
            case "User not authorized":
                return $this->sendError([], $response, 418);
            break;
            case "User level rights is low":
                return $this->sendError([], $response, 418);
            break;
            case "Images not selected":
                return $this->sendError([], $response, 418);
            break;
            case "Images deleted":
                return $this->sendResponse($request, $response);
            break;
        }      
    }
}
