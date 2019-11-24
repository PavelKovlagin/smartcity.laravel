<?php

use Illuminate\Database\Seeder;

class UsersSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name'=>'Админ',
            'surname'=>'Админов',
            'subname'=>'Админович',
            'date'=>'1995-05-01',
            'email'=>'admin@mail.ru',
            'role'=>'admin',
            'password'=>Hash::make('11111111')
        ]);
    }
}
