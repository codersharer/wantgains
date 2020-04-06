<?php

namespace App\Http\Controllers\Api;

use function addslashes;
use App\Http\Controllers\Controller;
use App\Repositories\SubscribeRepository;
use function dd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use function response;

class SubscribeController extends Controller
{
    /**
     * 邮件订阅
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mail' => 'required|max:255|email',
        ]);

        if ($validator->fails()) {
            $error = $validator->getMessageBag();
            return response()->json(['code' => 1, 'message' => $error->getMessages()['mail'][0]]);
        }
        $keywords = $request->post('keywords', []);
        $mail = $request->post('mail');
        SubscribeRepository::save(['mail' => $mail, 'keywords' => $keywords]);


        return response()->json(['code' => 0, 'message' => 'Thanks, we will notify you of the latest offers as soon as possible']);


    }
}
