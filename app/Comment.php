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
                ->join('roles', 'users.role_id', '=', 'roles.id')
                    ->select(
                'comments.id',
                'comments.user_id',
                'role_id',
                'roles.levelRights as user_levelRights',
                'email',
                'text',
                'dateTime')
                ->where("events.id", $event_id)
                ->orderBy('dateTime', 'asc');
        return $comments;
    }

    protected static function selectComment($comment_id) {
        $comments = DB::table('comments')
                    ->join('events', 'comments.event_id', '=', 'events.id')
                    ->join('users', 'comments.user_id', '=', 'users.id')
                    ->join('roles', 'users.role_id', '=', 'roles.id')
                     ->select(
                    'comments.id',
                    'comments.user_id',
                    'role_id',
                    'roles.levelRights as user_levelRights',
                    'email',
                    'text',
                    'dateTime')
                    ->where("comments.id", $comment_id)
                    ->orderBy('dateTime', 'asc')
                    ->first();
            return $comments;
    }

    protected static function addComment($request, $user_id) {
        if (Comment::selectComment($request->event_id) <> null) {
            $comment = new \App\Comment;
            $comment->user_id = $user_id;
            $comment->event_id = $request->event_id;
            $comment->text = $request->comment;
            $comment->dateTime = Carbon::now();
            $comment->save();
            return true;
        } else {
            return false;
        }

        
    }
}
