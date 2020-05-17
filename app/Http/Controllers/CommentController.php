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
        $comments = \App\Comment::selectCommentsFromEvent($event_id)->get();
        return $this->sendResponse($comments, count($comments));
    }
    //API добавления комментария
    public function apiAddComment(Request $request) {
        $validator = Validator::make($request->all(), [
            "comment" => "required",
        ]);
        if ($validator->fails()) {
            return $this->sendError('Error adding event.', $validator->errors(), 419);
        }
        $authUser = App\User::selectAuthUser();
        if ($authUser<>false){
            if ($authUser->blocked == false) {
                if (\App\Comment::addComment($request, \Auth::id())) {
                    return $this->sendResponse($request->all(), 'Comment added.');
                } else {
                    return $this->sendError($request->all(), 'Event not founded', 419);
                }
            } else return $this->sendError($request->all(), 'User blocked.', 419);
        } else {
            return $this->sendError($request->all(), 'User not authorization.', 419);
        }
    }
    //добавление комментария и перенаправление на страницу события с сообщением
    public function addComment(Request $request) {
        $authUser = App\User::selectAuthUser();
        if ($authUser<>false) {
            if ($authUser->blocked == false){
                    \App\Comment::addComment($request, Auth::user() -> id);
                    return redirect("/events/$request->event_id")->with("error", "Сообщение добавлено");
                } else {
                    return redirect("/events/$request->event_id")->with("error", "Вы не можете отправлять сообщения, Ваш профиль заблокирован до " . $authUser->blockDate);           
            } 
        } else {
            return redirect("/events/$request->event_id");
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
                return back()->with("error", "Сообщения не существует");
            break;
            case "Comment deleted":
                return back()->with("error", "Сообщение удалено");
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
