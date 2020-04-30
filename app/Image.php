<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App;
use DB;

class Image extends Model
{
    protected $fillable = [
        'name', 'user_id'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    protected static function selectImages(){
        $images = DB::table('images')
        ->join('users', 'user_id', '=', 'users.id')
        ->select("images.name as image_name")
        ->get();
        foreach ($images as $key => $image){
            if (Storage::disk("public")->exists($image->image_name) == false){        
                unset($images[$key]);        
                DB::table('images')->where('name', '=', $image->image_name)->delete();
            }
        }        
        return $images;
    }

    public static function insertImage($file_name, $user_id){
        $image = new Image;
        $image->name = $file_name;
        $image->user_id = $user_id;
        $image->save();
    }
}
