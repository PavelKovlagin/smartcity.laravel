<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Carbon\Carbon;
use App;
use DB;

class ImageController extends Controller
{
    private $image_ext = ['jgp', 'jpeg', 'png', 'gif'];

    public function selectImages(){
        $images = DB::table('images')
        ->select(
            'id',
            'name',
            'type',
            'extension'.
            'user_id');
        return $images;
    }

    public function deleteImage($file_name){
        Storage::delete($file_name);
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
            $i = 0;
            foreach ($request->images as $image){
                $i++;
                $file_name = Storage::putFileAs(
                    'public', $image, $currentDate.$i.'.jpg'
                );
                App\Image::insertImage($currentDate.$i.'.jpg', $authUser->user_id);
            }
            return $file_name;         
        }          
    }
}
