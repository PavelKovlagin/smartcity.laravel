<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {        
        $this->call(RolesTableSeeder::class);
        $this->call(UsersSeed::class);
        $this->call(StatusesSeed::class);
        $this->call(CategoriesTableSeeder::class);
        $this->call(EventsSeed::class);        
    }
}
