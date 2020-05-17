<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Role extends Model
{
    //запрос всех ролей
    protected static function selectRoles(){
        $roles = DB::table('roles')
        ->select(
            'id as role_id',
            'name as role_name',
            'levelRights as role_levelRights',
            'notRemove as role_notRemove');
        return $roles;
    }
    //запрос ролей, с правами ниже чем $levelRights
    protected static function selectRolesWithLevelRights($levelRights){
        $roles = Role::selectRoles()->where("levelRights", "<", $levelRights)->get();
        return $roles;
    }
}
