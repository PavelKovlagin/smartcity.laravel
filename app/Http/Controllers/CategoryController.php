<?php

namespace App\Http\Controllers;

use App\Categories;
use Illuminate\Http\Request;
use DB;
use App;

class CategoryController extends Controller
{
    public function showCategories() {
        $authUser = App\User::selectAuthUser();
        $categories = App\Category::selectcategories()->paginate(10);
        return view('categories.categories', [
            'authUser' => $authUser,
            'title' => 'Все категории',
            'categories' => $categories
        ]);
    }

    public function apiSelectCategories() {
        $categories = App\Category::selectCategories();
        return $categories;
    }

    public function showCategory($category_id) {
        $authUser = App\User::selectAuthUser();
        $category = App\Category::find($category_id);
        return view("categories.category", [
            'authUser' => $authUser,
            'category' => $category
        ]);
    }

    public function updateCategory(Request $request) {
        $authUser = App\User::selectAuthUser();
        $category = App\Category::find($request->id);
        if (($authUser <> false) AND ($authUser->levelRights > 2)) {
            App\Category::updateCategory($request);
            return redirect("/categories/$request->id");
        } else {
            return "У вас недостаточно прав";
        }
    }

    public function addCategory(Request $request){
        App\Category::insertCategory($request);
        return redirect("/categories");
    }

    public function deleteCategory(Request $request) {
        App\Event::changeCategory($request->id);
        $authUser = App\User::selectAuthUser();
        $category = App\Category::find($request->id);
        if (($authUser <> false) AND ($authUser->levelRights > 2) AND ($category->notRemove == 0) ) {
            App\Category::destroy($request->id);
            return redirect("/categories");
        } else {
            return redirect("/categories/$request->id");
        }
    }
}
