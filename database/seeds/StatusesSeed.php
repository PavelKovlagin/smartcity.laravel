<?php

use Illuminate\Database\Seeder;

class StatusesSeed extends Seeder
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
        ]);
    }
}
