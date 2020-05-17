<?php

namespace App\Http\Controllers;

use App\Categories;
use Illuminate\Http\Request;
use DB;
use App;

class CategoryController extends Controller
{
    //передача данных И открытие представления categories 
    public function showCategories() {
        $authUser = App\User::selectAuthUser();
        $categories = App\Category::selectcategories()->paginate(10);
        return view('categories.categories', [
            'authUser' => $authUser,
            'title' => 'Все категории',
            'categories' => $categories
        ]);
    }
    //возврат списка категорий в формате json
    public function apiSelectCategories() {
        $categories = App\Category::selectCategories()->get();
        return $this->sendResponse($categories, count($categories));
    }
    //передача данных и открытие представления category 
    public function showCategory($category_id) {
        $authUser = App\User::selectAuthUser();
        $category = App\Category::find($category_id);
        return view("categories.category", [
            'authUser' => $authUser,
            'category' => $category
        ]);
    }
    //обновление категории и перенаправление на страницу с обновленной категорией
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
    //добавление новой категории и перенаправление на страницу со списком категорий
    public function addCategory(Request $request){
        App\Category::insertCategory($request);
        return redirect("/categories");
    }
    //удаление категории и перенаправление на страницу со списком всех категорий
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
