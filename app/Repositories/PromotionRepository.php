<?php


namespace App\Repositories;


use App\Models\Promotion;
use function date;
use function dd;
use function is_array;
use function strtotime;
use function time;

class PromotionRepository
{
    /**
     * 根据商家id分组并获取可使用的优惠及促销信息
     *
     * @param $merchantId
     */
    public static function getListByMerchantId($merchantIds)
    {
        $result = [];
        if (is_array($merchantIds)) {
            $promotions = Promotion::whereIn('merchant_id', $merchantIds)->get()->toArray();
            if ($promotions) {
                foreach ($promotions as $key => $promotion) {
                    //判断是否已过期，过期则丢弃
                    if (($promotion['promotion_end_at'] != '') && (strtotime($promotion['promotion_end_at']) < time())) {
                        continue;
                    }
                    $result[$promotion['merchant_id']][Promotion::$scenes[$promotion['scenes']]][] = $promotion;
                }
            }
        } else {

        }

        return $result;
    }
}