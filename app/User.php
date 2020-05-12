<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Laravel\Passport\HasApiTokens;
use DB;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'surname', 'subname', 'date', 'role_id', 'email', 'password', 
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'code_reset_password', 'validity_password_reset_code', 'email_verified_at', 'created_at', 'updated_at', 'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected static function selectUsers(){
        $users = DB::table('users')
        ->join('roles', 'role_id', '=', 'roles.id')
        ->select(
            'users.id as user_id', 
            'users.name as user_name', 
            'surname',
            'subname',
            'date', 
            'email',
            'blockDate',
            'roles.id as role_id',
            'roles.name as role_name',
            'code_reset_password',
            'validity_password_reset_code',
            'levelRights',
            'notRemove',
        );
        return $users;
    }

    protected static function selectUser_email($email){
        $user = User::selectUsers()
        ->where('email', '=', $email)
        ->first();
        return $user;
    }

    protected static function selectUser($user_id) {
        $user = User::selectUsers()
        ->where('users.id', '=', $user_id)
        ->get()[0];
        if ($user->blockDate > Carbon::now()) {
            $user->blocked = "Заблокирован до " . $user->blockDate;
        } else {
            $user->blocked = false;
        }
        return $user;
    }

    protected static function selectAuthUser(){
        if (Auth::check()){
            return User::selectUser(Auth::user()->id);
        } else {
            return false;
        }
    }

    protected static function blockedUser($user_id, $blockDate){
        DB::update('UPDATE users SET blockDate = :blockDate WHERE id = :user_id',
        ['blockDate' => $blockDate, 'user_id' => $user_id]);
    }

    protected static function updateCodeResetPassword($user_id, $code_reset_password){
        DB::update('UPDATE users 
                SET 
                    code_reset_password = :code_reset_password,
                    validity_password_reset_code = :dateTime
                WHERE id = :user_id',
        ['code_reset_password' => Hash::make($code_reset_password), 'user_id' => $user_id, 'dateTime' => Carbon::now()->addHour()]);
        return true;
    }

    protected static function nullifyCodeResetPassword($user_id){
        DB::update('UPDATE users 
                SET 
                    code_reset_password = null,
                    validity_password_reset_code = null
                WHERE id = :user_id',
        ['user_id' => $user_id]);
        return true;
    }


    protected static function updateUser($user_id, $name, $surname, $subname, $date){
        DB::update('UPDATE users 
                SET name = :name, 
                    surname = :surname, 
                    subname = :subname, 
                    date = :date
                WHERE id = :user_id',
            ['user_id' => $user_id,
            'name' => $name,
            'surname' => $surname,
            'subname' => $subname,
            'date' => $date]);
            return true;
    }

    protected static function updateRole($user_id, $role_id){
        DB::update('update users set role_id = :role_id WHERE id = :user_id', 
        ['role_id' => $role_id, 'user_id' => $user_id]);
    }

    protected static function updatePassword($user_id, $password){
        DB::update('update users set password = :password WHERE id = :user_id', 
        ['password' => Hash::make($password), 'user_id' => $user_id]);
        return true;
    }

    protected static function selectOauthClient(){
        $oauth_client = DB::table('oauth_clients')
        ->select(
            'id',
            'user_id',
            'name',
            'secret',
            'redirect')
        ->where('name', '=', 'Laravel Password Grant Client')
        ->first();
        return $oauth_client;
    }
}
