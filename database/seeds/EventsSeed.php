<?php

use Illuminate\Database\Seeder;

class EventsSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('events')->insert([
        'user_id'=>1,
        'status_id'=>1,
        'longitude'=>56.148387,
        'latitude'=>40.389206,
        'eventName'=>'Тестовое событие. Рассматривается',
        'eventDescription'=>'Это событие рассматривается',
        'date'=>'2019-10-28',
        'dateChange'=>'2019-10-28'
      ]);
      DB::table('events')->insert([
        'user_id'=>1,
        'status_id'=>2,
        'longitude'=>56.146003,
        'latitude'=>40.391116,
        'eventName'=>'Тестовое событие. Отклонено',
        'eventDescription'=>'Это событие отклюнено',
        'date'=>'2019-10-28',
        'dateChange'=>'2019-10-28'
      ]);
      DB::table('events')->insert([
        'user_id'=>1,
        'status_id'=>3,
        'longitude'=>56.145063,
        'latitude'=>40.380831,
        'eventName'=>'Тестовое событие. Выполняется',
        'eventDescription'=>'Это событие выполняется',
        'date'=>'2019-10-28',
        'dateChange'=>'2019-10-28'
      ]);
      DB::table('events')->insert([
        'user_id'=>1,
        'status_id'=>4,
        'longitude'=>56.149591, 
        'latitude'=>40.377398,
        'eventName'=>'Тестовое событие. Завершено',
        'eventDescription'=>'Это событие завершено',
        'date'=>'2019-10-28',
        'dateChange'=>'2019-10-28'
      ]);
    }
}
