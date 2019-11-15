<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use DB;
use App;

class CommentController extends Controller
{
    public function addComment(Request $request) {
        $comment = new \App\Comment;
        $comment->user_id = Auth::user() -> id;
        $comment->event_id = $request->event_id;
        $comment->text = $request->comment;
        $comment->dateTime = Carbon::now();
        $comment->save();

        return redirect("/events/$request->event_id");
    }
}
