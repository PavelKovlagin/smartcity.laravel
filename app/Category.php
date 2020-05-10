<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Category extends Model
{
    protected static function selectCategories(){
        $categories = DB::table('categories')
        ->select(
            'id',
            'categoryName',
            'categoryDescription');
        return $categories;
    }

    protected static function selectCategory($category_id) {
        $category = Category::selectCategories()
        ->where("id", "=", $category_id)
        ->first();
        return $category;
    }

    protected static function insertCategory($request) {
        $category = new \App\Category;
        $category->categoryName = $request->categoryName;
        $category->categoryDescription = $request->categoryDescription;
        $category->save();
    }

    protected static function updateCategory($request) {
        DB::table('categories')
        ->where('id', '=', $request->id)
        ->update(array(
                    'categoryName' => $request->categoryName,
                    'categoryDescription' => $request->categoryDescription));
    }
}
