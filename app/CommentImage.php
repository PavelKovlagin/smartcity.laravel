<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage; 
use Carbon\Carbon;
use DB;

class CommentImage extends Model
{
    //запрос всех изображений для комментариев
    public static function selectCommentsImages() {
        $commentsImages = DB::table('comment_images')
        ->join('images', 'images.id', '=', 'image_id')
        ->join('users', 'users.id', '=', 'user_id')
        ->join('roles', "roles.id", "=", "users.role_id")
        ->select(
            'images.id as image_id', 
            'comment_id', 
            'images.name as image_name',
            'users.id as user_id',
            'roles.levelRights as user_levelRights'
        );
        return $commentsImages;
    }
    //запрос изображений для определенного по идентификатору комментария 
    public static function selectCommentImages($comment_id) {
        $commentImages = CommentImage::selectCommentsImages()
        ->where("comment_id", "=", $comment_id);
        return $commentImages;
    }
    //запрос определенного по идентификатору изображения
    public static function selectCommentImage($commentImage_id) {
        $commentImage = CommentImage::selectCommentsImages()
        ->where("image_id", "=", $commentImage_id)
        ->first();
        return $commentImage;
    }
    //запрос изображения по названию
    public static function selectCommentImage_name($image_name) {
        $commentImage = DB::table('comment_images')
        ->join('images', 'images.id', '=', 'image_id')
        ->where("images.name", "=", $image_name)
        ->select(
            'images.id as image_id', 
            'images.name as image_name',
        );
        return $commentImage;
    }
    //добавление изображений
    public static function insertCommentImages($images, $comment_id, $user_id){
        if ($images <> null) {
            $currentDate = Carbon::now()->year
                        .'.'.Carbon::now()->month  
                        .'.'.Carbon::now()->day
                        .' '.Carbon::now()->hour
                        .'-'.Carbon::now()->minute
                        .'-'.Carbon::now()->second;
            $i = 0;
            foreach ($images as $image){
                if (filesize($image) < 10000000){
                    $i++;
                    $file_name = Storage::putFileAs(
                        'public', $image, $currentDate.$i.'.jpg'
                    );
                    $image_id = Image::insertImage($currentDate.$i.'.jpg', $user_id);
                    CommentImage::insertCommentImage($image_id, $comment_id);
                }
            }
        }
    }
    //добавление изображения
    public static function insertCommentImage($image_id, $comment_id){
        $commentImage = new CommentImage;
        $commentImage->image_id = $image_id;
        $commentImage->comment_id = $comment_id;  
        $commentImage->save();
        return $commentImage->id;
    }
}
