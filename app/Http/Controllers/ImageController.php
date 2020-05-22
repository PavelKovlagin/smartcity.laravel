<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use Carbon\Carbon;
use App;
use DB;

class ImageController extends Controller
{
    //удаление изображений из файловой системы, если их нет в базе данных
    public function deleteImagesWithoutLink() {
        $files = Storage::files('public');
        $str = "";
        foreach($files as $file) {
            $fileArr = explode("/", $file);
            if ((!App\EventImage::selectEventImage_name($fileArr[1])->exists()) AND (!App\CommentImage::selectCommentImage_name($fileArr[1])->exists())){
                Storage::disk("public")->delete($fileArr[1]);
            }
        }
        return back();
    }

    public function uploadImage(Request $request){
        $authUser = App\User::selectAuthUser();
        $currentDate = Carbon::now()->year
                    .'.'.Carbon::now()->month  
                    .'.'.Carbon::now()->day
                    .' '.Carbon::now()->hour
                    .'-'.Carbon::now()->minute
                    .'-'.Carbon::now()->second;        
        if ($request->file('images') == null) {
            $file = "";
        }else{ 
            $stringIDs = "";
            $i = 0;
            foreach ($request->images as $image){
                $i++;
                $file_name = Storage::putFileAs(
                    'public', $image, $currentDate.$i.'.jpg'
                );
                $image_id = App\Image::insertImage($currentDate.$i.'.jpg', $authUser->user_id);
                $stringIDs = $stringIDs . " " . $image_id;               
            }
            return $stringIDs;         
        }          
    }
}
