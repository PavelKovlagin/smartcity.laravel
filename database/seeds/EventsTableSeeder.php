<?php

use Illuminate\Database\Seeder;

class EventsTableSeeder extends Seeder
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
        'category_id'=>1,
        'longitude'=>40.389206,
        'latitude'=>56.148387,
        'eventName'=>'Тестовое событие. Рассматривается',
        'eventDescription'=>'Это событие рассматривается',
        'date'=>'2019-10-28',
        'dateChange'=>'2019-10-28',
        'viewed' => 0
      ]);
      DB::table('events')->insert([
        'user_id'=>1,
        'status_id'=>2,
        'category_id'=>1,
        'longitude'=>40.391116,
        'latitude'=>56.146003,
        'eventName'=>'Тестовое событие. Отклонено',
        'eventDescription'=>'Это событие отклюнено',
        'date'=>'2019-10-28',
        'dateChange'=>'2019-10-28',
        'viewed' => 0
      ]);
      DB::table('events')->insert([
        'user_id'=>1,
        'status_id'=>3,
        'category_id'=>1,
        'longitude'=>40.380831,
        'latitude'=>56.145063,
        'eventName'=>'Тестовое событие. Выполняется',
        'eventDescription'=>'Это событие выполняется',
        'date'=>'2019-10-28',
        'dateChange'=>'2019-10-28',
        'viewed' => 0
      ]);
      DB::table('events')->insert([
        'user_id'=>1,
        'status_id'=>4,
        'category_id'=>1,
        'longitude'=>40.377398,
        'latitude'=>56.149591, 
        'eventName'=>'Тестовое событие. Завершено',
        'eventDescription'=>'Это событие завершено',
        'date'=>'2019-10-28',
        'dateChange'=>'2019-10-28',
        'viewed' => 0
      ]);
    }
}
