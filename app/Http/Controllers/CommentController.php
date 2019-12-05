<?php

namespace App\Http\Controllers;

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

    public function addComment(Request $request) {
        if (Auth::check()){
            \App\Comment::addComment($request, Auth::user() -> id);
            return redirect("/events/$request->event_id");
        } else {
            return("/events");
        }     
    }

    public function selectComments($event_id) {
        $comments = \App\Comment::selectCommentsFromEvent($event_id)->get();
        return $comments;
    }
}
