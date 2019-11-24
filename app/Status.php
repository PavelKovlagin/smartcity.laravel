<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Status extends Model
{
    protected static function selectStatuses(){
        $statuses = DB::table('statuses')
        ->select(
            'id',
            'statusName',
            'statusDescription')
        ->paginate(10);
        return $statuses;
    }

    protected static function insertStatus($request) {
        $status = new \App\Status;
        $status->statusName = $request->statusName;
        $status->statusDescription = $request->statusDescription;
        $status->save();
    }
}
