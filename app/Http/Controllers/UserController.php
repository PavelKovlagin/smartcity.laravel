<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use App;

class UserController extends Controller
{
    public function showUser($user_id) {
        $user = App\User::find($user_id);

        return view("users.user", [
            'user' => $user,
        ]);
    }

    public function showUsers(){
        $users = App\User::paginate(10);
        return view('users.users', [
            'title' => 'Все пользователи',
            'users' => $users,
        ]);
    }

    public function updateUser(Request $request) {
        \App\User::updateRole($request->user_id, $request->role);
        return redirect("/users");
    }

    public function deleteUser(Request $request) {
        if ((Auth::check()) and (Auth::user() -> role = "admin")) {
            App\User::destroy($request->user_id);

            return redirect("/users");
        } else {
            return redirect("/");
        }
    }
}
