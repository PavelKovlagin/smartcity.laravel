<?php

use Illuminate\Database\Seeder;

class CitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cities')->insert([
            'name'=>'Владимир',
            'longitude'=>'40.406635',
            'latitude'=>'56.129057',
        ]);
        DB::table('cities')->insert([
            'name'=>'Москва',
            'longitude'=>'37.622504',
            'latitude'=>'55.753215',
        ]);
        DB::table('cities')->insert([
            'name'=>'Омск',
            'longitude'=>'73.368212',
            'latitude'=>'54.989342',
        ]);
    }
}
