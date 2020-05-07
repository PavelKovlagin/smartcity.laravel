<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
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

    public function sendCode(Request $request) {
        $user = App\User::selectUser_email($request->email);
        if ($user == null){
            return back()->with('error', 'Пользователь не найден');
        } else {
            $codeResetPassword = random_int(100000, 999999);
            App\User::updateCodeResetPassword($user->user_id, $codeResetPassword);
            $to_name = $user->user_name;
            $to_email = $user->email;
            $data = array('name'=>$to_name, "body" => "Ваш код для сброса пароля: " . $codeResetPassword);
            Mail::send('emails/feedback', $data, function($message) use ($to_name, $to_email) {
                $message->to($to_email, $to_name)->subject('SmartCityVLSU Test');
                $message->from('SmartCityVLSU@gmail.com','SmartCity');
            });
            return redirect('/passwordChange')->with(['message' => 'Пароль отправлен на электронный адрес ' . $user->email]);
        }    
        
    }     

    public function passwordChange(Request $request) {
        $user = App\User::selectUser_email($request->email);
        if ($user == null) {
            return back()->with('message', 'Пользователь не найден');
        } else {
            if ((Hash::check($request->code_reset_password, $user->code_reset_password)) AND (Carbon::now() <= $user->validity_password_reset_code)) {
                if ($request->password == $request->password_confirm){
                    App\User::updatePassword($user->user_id, $request->password);
                    App\User::nullifyCodeResetPassword($user->user_id);
                    return redirect('/login');
                } else {
                    return back()->with(['message' => 'Пароли не совпадают']);
                }
            } else {
                return back()->with(['message' => 'Неверный либо просроченый код сбоса пароля']);
            }      
        } 
    }
}
