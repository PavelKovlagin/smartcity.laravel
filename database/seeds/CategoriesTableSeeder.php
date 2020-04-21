<?php

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->insert([
            'categoryName'=>"Other",
            'categoryDescription'=>"У данной категории нет описания",
            'notRemove'=>1
          ]);
    }
}
