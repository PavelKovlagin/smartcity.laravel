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
    //передача данных и открытие страницы с информацией о пользователе
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
    //передача данных и открытие страницы со списком всех пользователей
    public function showUsers(){
        $users = App\User::selectUsers()->paginate(10);
        $authUser = App\User::selectAuthUser(); 
        return view('users.users', [
            'currentDate' => Carbon::now(),
            'title' => 'Все пользователи',
            'authUser' => $authUser,
            'users' => $users]);
    }
    //блокировка пользователя до определенной даты и перенаправление на страницу со всеми пользователями
    public function blockedUser(Request $request) {
        \App\User::blockedUser($request->user_id, $request->blockDate);
        return redirect("/users");
    }
    //обновление информации о пользователе
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
    //api обновления информации о пользователе
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
    //обновление роли пользователя и перенаправление на страницу пользователя
    public function updateRole(Request $request) {
        \App\User::updateRole($request->user_id, $request->role_id);
        return redirect("/users/user/$request->user_id");
    }
    //api отправки кода сброса пароля пользователю
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
    //отправка кода сброса пароля пользователю
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
    //api изменения пароля
    public function apiPasswordChange(Request $request) {
        $user = App\User::selectUser_email($request->email);
        if ($user == null) return $this->sendError([$request->email], "User not found", 418);
        if (!(Hash::check($request->code_reset_password, $user->code_reset_password)) OR !(Carbon::now() <= $user->validity_password_reset_code)) 
            return  $this->sendError([$request->email], "Invalid or expired password reset code", 418);
        if ($request->password <> $request->password_confirm) return $this->sendError([$request->email], "Passwords don't match", 418);
        if ((App\User::updatePassword($user->user_id, $request->password)) AND (App\User::nullifyCodeResetPassword($user->user_id))) {
            return $this->sendResponse([$request->email], "Password was change");
        } else {
            return $this->sendError([$request->email], "Error", 418);
        }
    }
    //api запроса пользователя
    public function apiSelectUser(Request $request){
        $authUser = App\User::selectAuthUser();
        if ($authUser == null) return $this->sendError([], "Failed user", 418);
        return $this->sendResponse($authUser, $authUser->surname . " " . $authUser->user_name . " " . $authUser->subname);
    }
    //изменение пароля
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
    //api получение ключа клиента
    public function apiGetClientAuthentication(){
        $oauth_client = App\User::selectOauthClient();
        if ($oauth_client == null) return $this->sendError([], 'OauthClient null', 418);
        return $this->sendResponse($oauth_client, "OauthClient");
    }
}
