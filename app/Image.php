<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App;
use DB;

class Image extends Model
{
    //добавление изображения в таблицу images
    public static function insertImage($file_name, $user_id){
        $image = new Image;
        $image->name = $file_name;
        $image->user_id = $user_id;
        $image->save();
        return $image->id;
    }

    public static function checkExistsImages($images){
        foreach ($images as $key => $image){
            if (Storage::disk("public")->exists($image->image_name) == false){        
                unset($images[$key]);        
                DB::table('images')->where('name', '=', $image->image_name)->delete();
            }
        }   
        return $images;
    }
}
