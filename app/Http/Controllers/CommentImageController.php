<?php

namespace App\Http\Controllers;

use App\CommentImages;
use Illuminate\Http\Request;
use App;

class CommentImageController extends Controller
{
    //удаление изображения
    public function deleteCommentImages(Request $request){
        $comment = App\Comment::selectComment($request->comment_id);
        if ($comment == null) return "Comment not found";
        $authUser = App\User::selectAuthUser();
        $user = App\User::selectUser($comment->user_id);
        //return dd($authUser->levelRights . " " . $user->levelRights);
        if ($authUser == false) return "User not authorized";
        if (($authUser->levelRights < $user->levelRights) AND ($authUser->user_id <> $user->user_id)) return 'User level rights is low';        
        foreach ($request->comment_images_id as $comment_image_id) {
            App\CommentImage::destroy($comment_image_id);
        }       
        return "Images deleted";
    }        

    public function webDeleteCommentImages(Request $request){
        $response = $this->deleteCommentImages($request);
        switch ($response) {
            case "Comment not found":
                return back()->with(["error" => "Изображение не найдено"]);
            break;
            case "User not authorized":
                return back()->with(["error" => "Пользователь не авторизован"]);
            break;
            case "User level rights is low":
                return back()->with(["error" => "Недостаточный уровень прав пользователя"]);
            break;
            case "Images deleted":
                return back()->with(["error" => " Изображение удалено"]);
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
            case "Images deleted":
                return $this->sendResponse($request, $response);
            break;
        }      
    }
}
