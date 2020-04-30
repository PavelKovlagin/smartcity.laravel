<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Role extends Model
{
    protected static function selectRoles(){
        $roles = DB::table('roles')
        ->select(
            'id as role_id',
            'name as role_name',
            'levelRights as role_levelRights',
            'notRemove as role_notRemove')
            ->get();
        return $roles;
    }

    protected static function selectRolesWithLevelRights($levelRights){
        $roles = Role::selectRoles()->where("role_levelRights", "<", $levelRights);
        return $roles;
    }
}
