<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage; 
use Carbon\Carbon;
use DB;

class EventImage extends Model
{
    public static function selectEventImages($event_id) {
        $eventImages = DB::table('event_images')
        ->join('images', 'images.id', '=', 'image_id')
        ->where("event_id", "=", $event_id)
        ->select(
            'images.id as image_id', 
            'event_id', 
            'images.name as image_name',
            'user_id',
        );
        return $eventImages;
    }

    public static function selectEventImage($event_id, $eventImage_id) {
        $eventImage = EventImage::selectEventImages($event_id)
        ->where("image_id", "=", $eventImage_id)
        ->first();
        return $eventImage;
    }

    public static function insertEventImages($images, $event_id, $user_id){
        if ($images <> null) {
            $currentDate = Carbon::now()->year
                        .'.'.Carbon::now()->month  
                        .'.'.Carbon::now()->day
                        .' '.Carbon::now()->hour
                        .'-'.Carbon::now()->minute
                        .'-'.Carbon::now()->second;
            $i = 0;
            foreach ($images as $image){
                $i++;
                $file_name = Storage::putFileAs(
                    'public', $image, $currentDate.$i.'.jpg'
                );
                $image_id = Image::insertImage($currentDate.$i.'.jpg', $user_id);
                EventImage::insertEventImage($image_id, $event_id);
            }
        }
    }

    public static function insertEventImage($image_id, $event_id){
        $eventImage = new EventImage;
        $eventImage->image_id = $image_id;
        $eventImage->event_id = $event_id;  
        $eventImage->save();
        return $eventImage->id;
    }
}
