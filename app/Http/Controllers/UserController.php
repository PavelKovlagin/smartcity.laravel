<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use DB;
use App;

class UserController extends Controller
{
    public function showUser($user_id) {
        $authUser = App\User::selectAuthUser();
        $user = App\User::selectUser($user_id);
        $roles = App\Role::selectRolesWithLevelRights($authUser->levelRights);
        return view("users.user", [
            'roles' => $roles,
            'authUser' => $authUser,
            'user' => $user]);
    }

    public function showUsers(){
        $users = App\User::selectUsers()->paginate(10);
        $authUser = App\User::selectAuthUser(); 
        return view('users.users', [
            'currentDate' => Carbon::now(),
            'title' => 'Все пользователи',
            'authUser' => $authUser,
            'users' => $users]);
    }

    public function blockedUser(Request $request) {
        \App\User::blockedUser($request->user_id, $request->blockDate);
        return redirect("/users");
    }

    public function updateUser(Request $request){
        $authUser = App\User::selectAuthUser();
        $user = App\User::selectUser($request->user_id);
        if ($authUser <> false
            AND (($user->user_id == $authUser->user_id)
            OR ($authUser->levelRights > $user->levelRights))){
            \App\User::updateUser($request->user_id, 
                                $request->name, 
                                $request->surname, 
                                $request->subname, 
                                $request->date);
        }
        return redirect("/users/user/$request->user_id");        
    }

    public function updateRole(Request $request) {
        \App\User::updateRole($request->user_id, $request->role_id);
        return redirect("/users/user/$request->user_id");
    }

    public function deleteUser(Request $request) {
        $authUser = App\User::selectAuthUser();
        $user = App\User::selectUser();
        if (($authUser <> false) AND ($authUser -> levelRights > 1) AND ($authUser -> levelRights > $user -> levelRights)) {
            App\User::destroy($request->user_id);
            return redirect("/users");
        } else {
            return redirect("/");
        }
    }
}
