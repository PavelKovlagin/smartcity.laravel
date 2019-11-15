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
        $i = 1;
        while ($i < 10) {
            DB::table('events')->insert([
                'user_id'=>1,
                'longitude'=>54.212548,
                'latitude'=>48.251487,
                'nameEvent'=>'Событие №' . $i,
                'eventDescription'=>'Это событие рассматривается',
                'status'=>1,
                'date'=>'2019-10-28',
            ]);
            $i++;
        }
        while ($i < 20) {
            DB::table('events')->insert([
                'user_id'=>1,
                'longitude'=>51.212548,
                'latitude'=>68.251487,
                'nameEvent'=>'Событие №' . $i,
                'eventDescription'=>'Это событие в процессе выполнения',
                'status'=>2,
                'date'=>'2019-10-24',
            ]);
            $i++;
        }
        while ($i < 30) {
            DB::table('events')->insert([
                'user_id'=>1,
                'longitude'=>74.212548,
                'latitude'=>88.251487,
                'nameEvent'=>'Событие №' . $i,
                'eventDescription'=>'Это событие завершено',
                'status'=>3,
                'date'=>'2019-09-28',
            ]);
            $i++;
        }
    }
}
