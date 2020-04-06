<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTrack extends Model
{
    protected $table = 'user_tracks';
    const REDIS_KEY = ':user_tracks:';
    public static $source = [
        'category', //分类点击,
        'merchant',//商家点击
        'search',//搜索
    ];
}
