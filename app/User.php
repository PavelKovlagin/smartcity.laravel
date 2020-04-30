<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
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
        'name', 'surname', 'subname', 'date', 'role', 'email', 'password', 
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
            'levelRights',
            'notRemove',
        );
        return $users;
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
    }

    protected static function updateRole($user_id, $role_id){
        DB::update('update users set role_id = :role_id WHERE id = :user_id', 
        ['role_id' => $role_id, 'user_id' => $user_id]);
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
