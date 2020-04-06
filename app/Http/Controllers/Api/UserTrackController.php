<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserTrack;
use Illuminate\Http\Request;
use function addslashes;
use function in_array;

class UserTrackController extends Controller
{
    /**
     * 用户行为统计接口
     */
    public function index(Request $request)
    {
        //来源，目前就三种：点击分类、点击商家、搜索
        $source = $request->get('source');
        if (!in_array($source, UserTrack::$source)) {
            return;
        }
        $value = addslashes($request->get('value'));
        if (!$value) {
            return;
        }
        $cookieId = $request->get('track_id');
        if (!$cookieId) {
            return;
        }
        $mail = $request->get('mail');
        //这段代码保留，现在直接入库，以后可能，可能，可能量大再走对列
        //        $redis = app('redis.connection');
        //        $redis->lpush(UserTrack::REDIS_KEY, json_encode(['source' => $source, 'value' => $value, 'track_id' => $cookieId]));
        $data['source'] = $source;
        $data['value'] = $value;
        $data['track_id'] = $cookieId;
        $data['mail'] = $mail ?? '';
        $data['updated_at'] = date('Y-m-d H:i:s', time());
        $data['created_at'] = date('Y-m-d H:i:s', time());
        UserTrack::insert($data);
        return;


    }
}
