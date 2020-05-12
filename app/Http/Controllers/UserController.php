<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use DB;
use App;

class UserController extends Controller
{
    public function showUser($user_id) {
        $user = App\User::selectUser($user_id);
        $authUser = App\User::selectAuthUser();
        if ($authUser <> false) {
            $roles = App\Role::selectRolesWithLevelRights($authUser->levelRights);
        } else {
            $roles = [];
        }        
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

    public function apiUpdateUser(Request $request) {    
        $validator = Validator::make($request->all(), [
            "user_id" => "required",
        ]);
        if ($validator->fails()) {
            return $this->sendError('Failed user update. Validation failed', $validator->errors(), 418);
        }
        $authUser = App\User::selectAuthUser();
        $user = App\User::selectUser($request->user_id);
        if ($authUser == false) return $this->sendError($request->all(), "Don't authorization", 418);
        if ($authUser->user_id <> $user->user_id) return $this->sendError($request->all(), "It's not your profile. Go away",418);
        if (App\User::updateUser($request->user_id, $request->name, $request->surname, $request->subname, $request->date)) {
            return $this->sendResponse($request->all(), "User update");
        } else {
            return $this->sendError($request->all(), "Failed user update", 419);
        }
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

    public function apiSendCode(Request $request) {
        $user = App\User::selectUser_email($request->email);
        if ($user == null) return $this->sendError($request->all(), "User not found", 418);
        $codeResetPassword = random_int(100000, 999999);
        $data = array('name'=>$user->user_name, "body" => "Ваш код для сброса пароля: " . $codeResetPassword);
        
        if (App\User::updateCodeResetPassword($user->user_id, $codeResetPassword) AND $this->send($user->user_name, $user->email, $data)) {
            return $this->sendResponse([], "Reset code was sent on email: " . $user->email);
        } else {
            $this->sendError([], "Reset code not was sent", 418);
        }
    }

    public function sendCode(Request $request) {
        $user = App\User::selectUser_email($request->email);
        if ($user == null){
            return back()->with('error', 'Пользователь не найден');
        } else {
            $codeResetPassword = random_int(100000, 999999);
            App\User::updateCodeResetPassword($user->user_id, $codeResetPassword);
            $data = array('name'=>$user->user_name, "body" => "Ваш код для сброса пароля: " . $codeResetPassword);
            $this->send($user->user_name, $user->email, $data);
            return redirect('/passwordChange')->with(['message' => 'Код сброса пароля отправлен на электронный адрес ' . $user->email]);
        }            
    }
    
    public function apiPasswordChange(Request $request) {
        $user = App\User::selectUser_email($request->email);
        if ($user == null) return $this->sendError($request->all(), "User not found", 418);
        if (!(Hash::check($request->code_reset_password, $user->code_reset_password)) AND !(Carbon::now() <= $user->validity_password_reset_code)) 
            return  $this->sendError($request->all(), "Invalid or expired password reset code", 418);
        if ($request->password <> $request->password_confirm) return $this->sendError($request->all(), "Passwords don't match", 418);
        if ((App\User::updatePassword($user->user_id, $request->password)) AND (App\User::nullifyCodeResetPassword($user->user_id))) {
            return $this->sendResponse($request->all(), "Password was change");
        } else {
            return $this->sendError($request->all(), "Error", 418);
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

    public function apiGetClientAuthentication(){
        return $this->sendResponse(App\User::selectOauthClient(), 'OauthClient');

    }
}
