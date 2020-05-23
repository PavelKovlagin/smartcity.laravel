<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage; 
use Carbon\Carbon;
use DB;

class EventImage extends Model
{
    //запрос всех изображений для событий
    public static function selectEventsImages() {
        $eventsImages = DB::table('event_images')
        ->join('images', 'images.id', '=', 'image_id')
        ->join('users', 'users.id', '=', 'user_id')
        ->join('roles', "roles.id", "=", "users.role_id")
        ->select(
            'event_images.id as event_image_id',
            'images.id as image_id', 
            'event_id', 
            'images.name as image_name',
            'users.id as user_id',
            'roles.levelRights as user_levelRights'
        );
        return $eventsImages;
    }

    public static function selectImages($images_id){
        $images = EventImage::selectEventsImages()
        ->whereIn("event_images.id", $images_id);
        return $images;
    }
    //запрос изображений для определенного по идентификатору события 
    public static function selectEventImages($event_id) {
        $eventImages = EventImage::selectEventsImages()
        ->where("event_id", "=", $event_id);
        return $eventImages;
    }
    //запрос определенного по идентификатору изображения
    public static function selectEventImage($eventImage_id) {
        $eventImage = EventImage::selectEventsImages()
        ->where("event_images.id", "=", $eventImage_id)
        ->first();
        return $eventImage;
    }
    //запрос изображения по названию
    public static function selectEventImage_name($image_name) {
        $eventImage = DB::table('event_images')
        ->join('images', 'images.id', '=', 'image_id')
        ->where("images.name", "=", $image_name)
        ->select(
            'images.id as image_id', 
            'images.name as image_name',
        );
        return $eventImage;
    }
    //добавление изображений
    public static function insertEventImages($images, $event_id, $user_id){
        if ($images <> null) {
            $currentDate = Carbon::now()->year
                        .'.'.Carbon::now()->month  
                        .'.'.Carbon::now()->day
                        .' '.Carbon::now()->hour
                        .'-'.Carbon::now()->minute
                        .'-'.Carbon::now()->second;
            $i = 0;
            $string = "";
            foreach ($images as $image){
                if (filesize($image) < 10000000){
                    $i++;
                    $file_name = Storage::putFileAs(
                        'public', $image, $currentDate.$i.'.jpg'
                    );
                    $image_id = Image::insertImage($currentDate.$i.'.jpg', $user_id);
                    EventImage::insertEventImage($image_id, $event_id);
                    $string = $string . ' ' . $file_name;
                }
            }
        }
    }
    //добавление изображения
    public static function insertEventImage($image_id, $event_id){
        $eventImage = new EventImage;
        $eventImage->image_id = $image_id;
        $eventImage->event_id = $event_id;  
        $eventImage->save();
        return $eventImage->id;
    }
}
