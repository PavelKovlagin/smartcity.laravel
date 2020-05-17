<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
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
            'role_id'=>1,
            'password'=>Hash::make('11111111')
        ]);
        DB::table('users')->insert([
            'name'=>'Модератор',
            'surname'=>'Модераторович',
            'subname'=>'Модераторов',
            'date'=>'1995-05-01',
            'email'=>'moderator@mail.ru',
            'role_id'=>2,
            'password'=>Hash::make('11111111')
        ]);
        DB::table('users')->insert([
            'name'=>'Пользователь',
            'surname'=>'Пользователев',
            'subname'=>'Пользователев',
            'date'=>'1995-05-01',
            'email'=>'user@mail.ru',
            'role_id'=>3,
            'password'=>Hash::make('11111111')
        ]);
    }
}
