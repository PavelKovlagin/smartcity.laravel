<?php

use Illuminate\Database\Seeder;

class StatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('statuses')->insert([
            'statusName'=>"Рассматривается",
            'statusDescription'=>"Это событие находится в стадии рассмотрения и не выводится в общем списке",
            'notRemove'=>1
        ]);
        DB::table('statuses')->insert([
            'statusName'=>"Отклонено",
            'statusDescription'=>"Это событие отклонено и не выводится в общем списке",
            'visibilityForUser'=>0,
            'notRemove'=>1
        ]);
        DB::table('statuses')->insert([
            'statusName'=>"Выполняется",
            'statusDescription'=>"Это событие выполняется и выводится в общем списке",
            'visibilityForUser'=>1,
            'notRemove'=>1
        ]);
        DB::table('statuses')->insert([
            'statusName'=>"Завершено",
            'statusDescription'=>"Это событие завершено и выводится в общем списке",
            'visibilityForUser'=>1,
            'notRemove'=>1
        ]);
    }
}
