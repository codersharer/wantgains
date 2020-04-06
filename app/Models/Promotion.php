<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{

    CONST ACTIVE_STATUS = 1;
    CONST INACTIVE_STATUS = 0;

    CONST TYPE_DEAL = 1;
    CONST TYPE_COUPON = 2;

    //全场优惠或折扣
    CONST SCENES_ALL_SITE = 1;
    //第一单优惠
    CONST SCENES_FIRST_ORDER = 2;
    //特定商品优惠
    CONST SCENES_SPECIAL_PRODUCT = 3;

    CONST REDIS_KEY = ':PROMOTION:';

    CONST DISCOUNT_UNIT_PERCENT = 'percent';
    CONST DISCOUNT_UNIT_CURRENCY = 'currency';


    public static $scenes = [
        self::SCENES_ALL_SITE        => 'all_site',
        self::SCENES_FIRST_ORDER     => 'first_order',
        self::SCENES_SPECIAL_PRODUCT => 'special_product',
    ];


    protected $fillable = [
        'affiliate_id',
        'domain_id',
        'domain',
        'name',
        'type',
        'coupon_code',
        'url',
        'scenes' . 'promotion_start_at',
        'promotion_end_at',
        'status',
        'description',
    ];
}
