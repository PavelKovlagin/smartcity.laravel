<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App;

use Illuminate\Http\Request;

class StatusController extends Controller
{
    public function showStatuses() {
        $statuses = App\Status::selectStatuses()->paginate(10);
        return view('statuses.statuses', [
            'title' => 'Все статусы',
            'statuses' => $statuses
        ]);
    }

    public function apiSelectStatuses() {
        $statuses = App\Status::selectVisibilityStatuses()->get();
        return $this->sendResponse($statuses, count($statuses));
    }

    public function showStatus($status_id) {
        $status = App\Status::find($status_id);
        $authUser = App\User::selectAuthUser();
        return view("statuses.status", [
            'authUser' => $authUser,
            'status' => $status
        ]);
    }

    public function updateStatus(Request $request) {
        $authUser = App\User::selectAuthUser();
        if (($authUser<>false) AND ($authUser->levelRights > 2)) {
            App\Status::updateStatus($request);
            return redirect("/statuses/$request->status_id");
        } else {
            return "У вас недостаточно прав";
        }
    }

    public function deleteStatus(Request $request) {
        App\Event::changeStatus($request->status_id);
        $authUser = App\User::selectAuthUser();
        $status = App\Status::find($request->status_id);
        if (($authUser <> false) AND ($authUser->levelRights > 2) AND ($status->notRemove == 0)) {
            App\Status::destroy($request->status_id);
            return redirect("/statuses");
        } else {
            return redirect("/statuses/$request->status_id");
        }
    }

    public function addStatus(Request $request){
        \App\Status::insertStatus($request);
        return redirect("/statuses");
    }
}
