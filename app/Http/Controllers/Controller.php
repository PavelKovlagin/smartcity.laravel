<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use DB;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    //возврат ответа в виде json, который обозначает успешность выполнения
    public function sendResponse($result, $message) {
        $response = [
            'success' => true,
            'data' => $result,
            'message' => $message
        ];
        return response()->json($response, 200);
    }
    //возврат ответа в виде json, который обозначает что произошла ошибка
    public function sendError($result, $message, $code) {
        $response = [
            'success' => false,
            'data' => $result,
            'message' => $message,
        ];
        return response()->json($response, $code);
    }
    //отправка письма на электронный адрес
    public function send($to_name, $to_email, $data) {
        Mail::send('emails/feedback', $data, function($message) use ($to_name, $to_email) {
            $message->to($to_email, $to_name)->subject('SmartCityVLSU Test');
            $message->from('SmartCityVLSU@gmail.com','SmartCity');
        });
        return true;
    }
    //проверка наличия изображений в файловой системе
    public function checkExistsImages($images){
        foreach ($images as $key => $image){
            if (Storage::disk("public")->exists($image->image_name) == false){        
                unset($images[$key]);        
                DB::table('images')->where('name', '=', $image->image_name)->delete();
            }
        }   
        return $images;
    }
}
