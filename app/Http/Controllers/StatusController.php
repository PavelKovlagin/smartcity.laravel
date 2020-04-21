<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
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

    public function showStatus($status_id) {
        $status = App\Status::find($status_id);
        return view("statuses.status", [
            'status' => $status
        ]);
    }

    public function updateVisibility(Request $request) {
        if (Auth::check() and Auth::user()->role == "admin") {
            $status_id = $request->id;
            $visibility = $request->visibilityForUser;
            App\Status::updateVisibility($status_id, $visibility);
            return redirect("/statuses");
        } else {
            return "У вас недостаточно прав";
        }
    }

    public function deleteStatus(Request $request) {
        if (Auth::check() AND Auth::user() -> role = "admin" AND $request->notRemove == 0) {
            App\Status::destroy($request->status_id);
            return redirect("/statuses");
        } else {
            return redirect("/");
        }
    }

    public function addStatus(Request $request){
        \App\Status::insertStatus($request);
        return redirect("/statuses");
    }
}
