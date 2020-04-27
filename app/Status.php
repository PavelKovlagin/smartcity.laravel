<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use DB;

class Status extends Model
{
    protected static function selectStatuses(){
        $statuses = DB::table('statuses')
        ->select(
            'id',
            'statusName',
            'statusDescription',
            'visibilityForUser');
        return $statuses;
    }

    protected static function selectVisibilityStatuses() {
        $statuses = Status::selectStatuses()
        ->where("visibilityForUser", "=", 1);
        return $statuses;
    }

    protected static function insertStatus($request) {
        $status = new \App\Status;
        $status->statusName = $request->statusName;
        $status->statusDescription = $request->statusDescription;
        $status->visibilityForUser = $request->visibilityForUser;
        $status->save();
    }

    protected static function updateStatus($request) {
        DB::table('statuses')
        ->where('id', '=', $request->status_id)
        ->update(array(
            'statusName' => $request->status_name,
            'statusDescription' => $request->status_description,
            'visibilityForUser' => $request->visibilityForUser));
    }
}
