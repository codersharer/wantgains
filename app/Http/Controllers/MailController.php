<?php

namespace App\Http\Controllers;

use function dd;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    /**
     * 订阅邮件
     */
    public function subscribe()
    {
        Mail::send('mail.subscribe', [

        ], function ($message) {
            $message->to('ss18918569787@gmail.com')->subject('test');
        });
        dd(Mail::failures());
    }
}
