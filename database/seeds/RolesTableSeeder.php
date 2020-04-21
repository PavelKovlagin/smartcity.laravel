<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            'name'=>"admin",
            'levelRights'=>3,
            'notRemove'=>true
          ]);
          DB::table('roles')->insert([
            'name'=>"moderator",
            'levelRights'=>2,
            'notRemove'=>true
          ]);
          DB::table('roles')->insert([
            'name'=>"user",
            'levelRights'=>1,
            'notRemove'=>true
          ]);
    }
}
