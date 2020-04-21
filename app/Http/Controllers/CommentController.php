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
        return $comments;
    }

    public function apiAddComment(Request $request) {
        if (Auth::user() -> blockDate < Carbon::now()){
            $validator = Validator::make($request->all(), [
                "comment" => "required",
            ]);
            if ($validator->fails()) {
                return $this->sendError('Error adding event.', $validator->errors());
            }
            \App\Comment::addComment($request, \Auth::id());
            return $this->sendResponse($request->all(), 'Comment added.');
        } else {
            return $this->sendError($request->all(), 'Comment not added.', 200);
        }
    }

    public function addComment(Request $request) {
        if (Auth::check() AND Auth::user() -> blockDate < Carbon::now()){
            \App\Comment::addComment($request, Auth::user() -> id);
            return redirect("/events/$request->event_id")->with("error", "Сообщение добавилено");
        } else {
            return redirect("/events/$request->event_id")->with("error", "Вы не можете отправлять сообщения, Ваш профиль заблокирован");
            
        }     
    }

    public function selectComments($event_id) {
        $comments = \App\Comment::selectCommentsFromEvent($event_id)->get();
        return $comments;
    }
}
