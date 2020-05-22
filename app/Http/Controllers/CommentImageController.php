<?php

namespace App\Http\Controllers;

use App\CommentImages;
use Illuminate\Http\Request;

class CommentImagesController extends Controller
{
    //удаление изображения
    public function deleteCommentImage(Request $request){
        $eventImage = App\CommentImage::selectCommentImage($request->image_id);
        if ($commentImage == null) return "Comment image not found";
        $authUser = App\User::selectAuthUser();
        $user = App\User::selectUser($commentImage->user_id);
        if ($authUser == false) return "User not authorized";
        if (($authUser->levelRights < $user->levelRights) OR ($authUser->user_id <> $user->user_id)) return 'User level rights is low';
        App\CommentImage::destroy($request->image_id);
        return "Image deleted";
    }        

    public function webDeleteCommentImage(Request $request){
        $response = $this->deleteCommentImage($request);
        switch ($response) {
            case "Comment image not found":
                return back()->with(["error" => "Изображение не найдено"]);
            break;
            case "User not authorized":
                return back()->with(["error" => "Пользователь не авторизован"]);
            break;
            case "User level rights is low":
                return back()->with(["error" => "Недостаточный уровень прав пользователя"]);
            break;
            case "Image deleted":
                return back()->with(["error" => " Изображение удалено"]);
            break;
        }
    } 

    //api удаления изображения
    public function apiDeleteCommentImage(Request $request){
        $response = $this->deleteCommentImage($request);
        switch ($response) {
            case "Comment image not found":
                return $this->sendError([], $response, 418);
            break;
            case "User not authorized":
                return $this->sendError([], $response, 418);
            break;
            case "User level rights is low":
                return $this->sendError([], $response, 418);
            break;
            case "Image deleted":
                return $this->sendResponse($request, $response);
            break;
        }      
    }
}
