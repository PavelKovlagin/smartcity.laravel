<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\FeedbackMail;

class FeedbackController extends Controller
{
    public function send() {
        $to_name = 'Pavel';
        $to_email = 'pashokkov@mail.ru';
        $data = array('name'=>$to_name, "body" => "Hello it's smart city");
        Mail::send('emails/feedback', $data, function($message) use ($to_name, $to_email) {
            $message->to($to_email, $to_name)->subject('SmartCityVLSU Test');
            $message->from('SmartCityVLSU@gmail.com','SmartCity');
        });
        return 'Сообщение отправлено на адрес '. $to_email;
    }
}
