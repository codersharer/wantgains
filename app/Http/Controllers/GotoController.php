<?php

namespace App\Http\Controllers;

use App\Models\OutGoing;
use App\Repositories\OutGoingRepository;
use App\Repositories\ProductRepository;
use App\Services\OutGoing\Handler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use function dd;
use function json_decode;
use function json_encode;
use function redirect;
use function str_replace;
use function urlencode;

class GotoController extends Controller
{
    public function out(Request $request, string $domain)
    {
        $outgoingInfo = Cache::store('redis')->get(Handler::OUTGOING_KEY . $domain);
        $outgoingInfo = json_decode($outgoingInfo, true);
        $sid = Str::random(64);
        $outgoingInfo['track_link'] = str_replace("[SUBTRACKING]", $sid, $outgoingInfo['track_link']);
        //g参数为deepurl参数
        if ($request->get('g')) {
            $outgoingInfo['track_link'] = str_replace("[DEEPURL]", urlencode("https://" . $request->get('g')),
                $outgoingInfo['track_link']);
        } else {
            $outgoingInfo['track_link'] = str_replace("[DEEPURL]", urlencode("https://" . $domain),
                $outgoingInfo['track_link']);
        }
        $outgoingInfo['ip'] = $request->getClientIp();
        $outgoingInfo['user_agent'] = $request->userAgent();
        $outgoingInfo['sid'] = $sid;
        $outgoingInfo['type'] = 'deal';
        //这里会有问题的最好是放入队列
        OutGoingRepository::saveTracking($outgoingInfo);

        return redirect()->away($outgoingInfo['track_link']);
    }


    public function productOut(Request $request, string $productId)
    {
        $outgoingInfo = Cache::store('redis')->get(Handler::PRODUCT_OUTGOING_KEY . $productId);
        $outgoingInfo = json_decode($outgoingInfo, true);
        if (empty($outgoingInfo)) {
            $outgoingInfo = ProductRepository::getInfo('id', $productId);
        }
        $sid = Str::random(64);
        $outgoingInfo['track_link'] = str_replace("[SUBTRACKING]", $sid, $outgoingInfo['track_link']);
        //g参数为deepurl参数
        if ($request->get('g')) {
            $outgoingInfo['track_link'] = str_replace("[DEEPURL]", urlencode("https://" . $request->get('g')),
                $outgoingInfo['track_link']);
        } else {
            $outgoingInfo['track_link'] = str_replace("[DEEPURL]", urlencode("https://" . $outgoingInfo['domain']),
                $outgoingInfo['track_link']);
        }
        $outgoingInfo['ip'] = $request->getClientIp();
        $outgoingInfo['user_agent'] = $request->userAgent();
        $outgoingInfo['sid'] = $sid;
        $outgoingInfo['type'] = 'product';
        $outgoingInfo['product_id'] = $productId;
        //这里会有问题的最好是放入队列
        //入队列
        $redis = app('redis.connection');
        $redis->lpush(OutGoing::OUTGOING_KEY, json_encode($outgoingInfo));

        return redirect()->away($outgoingInfo['track_link']);
    }
}
