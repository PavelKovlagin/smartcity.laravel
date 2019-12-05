<?php

namespace App\Http\Controllers;
use App;

use Illuminate\Http\Request;

class StatusController extends Controller
{
    public function showStatuses() {
        $statuses = App\Status::selectStatuses();
        return view('statuses.statuses', [
            'title' => 'Все статусы',
            'statuses' => $statuses
        ]);
    }

    public function apiSelectStatuses() {
        $statuses = App\Status::selectVisibilityStatuses();
        return $statuses;
    }

    public function addStatus(Request $request){
        \App\Status::insertStatus($request);
        return redirect("/statuses");
    }
}
