<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use DB;
use App;

class CommentController extends Controller
{
    //возвращение списка комментариев для события в формате json
    public function apiSelectComments() {
        if(!request()->has('event_id')) return "false";
        $event_id = request('event_id');
        $comments = \App\Comment::selectCommentsFromEvent($event_id);
        return $this->sendResponse($comments, count($comments));
    }

    //добавление комментария и перенаправление на страницу события с сообщением
    public function addComment(Request $request) {        
        $validator = Validator::make($request->all(), [
            "comment" => "required",
        ]);
        if ($validator->fails()) return array("response" => "Validation failed", "validator" => $validator->errors());
        $authUser = App\User::selectAuthUser();
        if ($authUser == false) return array("response" => "User not authorized");
        if ($authUser->blocked <> false) return array("response" => "User blocked", "dateBlock" => $authUser->blockDate);
        $comment_id = \App\Comment::addComment($request, Auth::user() -> id);        
        if ($comment_id > 0) 
        {
            App\CommentImage::insertCommentImages($request->images, $comment_id, $authUser->user_id);
            return array("response" => "Comment added");  
        } else {
            return array("response" => "Comment not added");
        }
    }

    public function webAddComment(Request $request){
        
        $response = $this->addComment($request);
        switch ($response["response"]) {
            case "Validation failed":
                return back()->with(["error" => "Валидация не пройдена: " . $response["validator"]]);
            break;
            case "User not authorized":
                return back()->with(["error" => "Пользователь не авторизован"]);
            break;
            case "User blocked":
                return back()->with(["error" => "Пользователь заблокирован до " . $response["dateBlock"]]);
            break;
            case "Comment not added":
                return back()->with(["error" => "Комментарий не добавлен"]);
            break;
            case "Comment added":
                return back()->with(["error" => "Комментарий добавлен"]);
            break;            
        }
    }

    //API добавления комментария
    public function apiAddComment(Request $request) {
        $response = $this->addComment($request);
        switch ($response["response"]) {
            case "Validation failed":
                return $this->sendError($response["validator"], $response["response"], 418);
            break;
            case "User not authorized":
                return $this->sendError([], $response["response"], 418);
            break;
            case "User blocked":
                return $this->sendError([], $response["response"] . " " . $response["dateBlock"], 418);
            break;
            case "Comment not added":
                return $this->sendError($request->all(), $response["response"], 418);
            break;
            case "Comment added":
                return $this->sendResponse($request->all(), $response["response"]);
            break;
        }
    }

    //удаление комменатия
    public function deleteComment(Request $request){
        $authUser = App\User::selectAuthUser();
        $comment = App\Comment::selectComment($request->comment_id);
        if (empty($comment)){
            return "Comment not found";
        }  else {
            if (($authUser<>false)
            AND (($authUser->levelRights > $comment->user_levelRights)
            OR ($authUser->user_id == $comment->user_id))){ 
                App\Comment::destroy($request->comment_id);   
                return "Comment deleted" ;                      
            } else {
                return "LevelRights fail";                
            }
        }        
    }
    //удаление комментария и перенаправление на предыдущую страницу
    public function wedDeleteComment(Request $request) {
        $response = $this->deleteComment($request);
        switch ($response) {
            case "Comment not found":
                return back()->with("error", "Комментария не существует");
            break;
            case "Comment deleted":
                return back()->with("error", "Комментарий удалён");
            break;
            case "LevelRights fail":
                return back()->with("error", "Недостаточно прав для удаления");
            break;
        }
    }
    //удаление комментария и возвращене ответа в формате json
    public function apiDeleteComment(Request $request) {
        $response = $this->deleteComment($request);
        switch ($response) {
            case "Comment not found":
                return $this->sendError($request->all(), "Comment not fount", 418);
            break;
            case "Comment deleted":
                return $this->sendResponse($request->all(), "Comment deleted");
            break;
            case "LevelRights fail":
                return $this->sendError($request->all(), "LevelRights fail", 418);
            break;
        }
    }
}
