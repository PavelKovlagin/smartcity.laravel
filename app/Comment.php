<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Comment extends Model
{
    protected static function selectCommentsFromEvent($id) {
            $comments = DB::table('comments')
                    ->join('events', 'comments.event_id', '=', 'events.id')
                    ->join('users', 'comments.user_id', '=', 'users.id')
                     ->select(
                    'email',
                    'text',
                    'dateTime'
                    )
                    ->where("events.id", $id)
                    ->orderBy('dateTime', 'asc')
                    ->get();
            return $comments;
    }
}
