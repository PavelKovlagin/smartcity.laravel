<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use Carbon\Carbon;
use App;
use DB;

class ImageController extends Controller
{
    private $image_ext = ['jgp', 'jpeg', 'png', 'gif'];

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
