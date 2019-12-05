<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use DB;

class Comment extends Model
{
    protected static function selectCommentsFromEvent($event_id) {
            $comments = DB::table('comments')
                    ->join('events', 'comments.event_id', '=', 'events.id')
                    ->join('users', 'comments.user_id', '=', 'users.id')
                     ->select(
                    'email',
                    'text',
                    'dateTime')
                    ->where("events.id", $event_id)
                    ->orderBy('dateTime', 'asc');
            return $comments;
    }

    protected static function addComment($request, $user_id) {
        $comment = new \App\Comment;
        $comment->user_id = $user_id;
        $comment->event_id = $request->event_id;
        $comment->text = $request->comment;
        $comment->dateTime = Carbon::now();
        $comment->save();
    }
}
