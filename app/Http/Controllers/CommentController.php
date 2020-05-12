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
    public function apiSelectComments() {
        if(!request()->has('event_id')) return "false";
        $event_id = request('event_id');
        $comments = \App\Comment::selectCommentsFromEvent($event_id)->get();
        return $this->sendResponse($comments, count($comments));
    }

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

    public function deleteComment(Request $request){
        $authUser = App\User::selectAuthUser();
        $comment = App\Comment::selectComment($request->comment_id);
        if (empty($comment)){
            return back()->with("error", "Сообщения не существует");
        }  else {
            if (($authUser<>false)
            AND (($authUser->levelRights > $comment->user_levelRights)
            OR ($authUser->user_id == $comment->user_id))){ 
                App\Comment::destroy($request->comment_id);                            
                return back()->with("error", "Сообщение удалено");
            } else {
                return back()->with("error", "Недостаточно прав для удаления");
            }
        }        
    }
}
