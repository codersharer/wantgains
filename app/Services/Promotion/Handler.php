<?php


namespace App\Services\Promotion;


use App\Models\Promotion;
use function dd;

class Handler
{
    protected $product;

    /**
     * 传入商品根据策略选出最优coupon或deal
     *
     * @param $product
     * @param $promotions
     */
    public static function handle($product, $promotions)
    {
        //先根据商品价格计算出优惠的最终可能价格
        $result = [];
        if (empty($promotions)) {
            return $result;
        }
        foreach ($promotions as $scene => $promotion) {
            foreach ($promotion as $item) {
                if ($item['scenes'] == Promotion::SCENES_ALL_SITE) {
                    switch ($item['discount_unit']) {
                        case Promotion::DISCOUNT_UNIT_PERCENT:
                            $price = $product['real_price'] * (1 - $item['discount'] / 100);
                            if (empty($result[Promotion::$scenes[Promotion::SCENES_ALL_SITE]]['price']) or ($price <
                                    $result[Promotion::$scenes[Promotion::SCENES_ALL_SITE]]['price'])) {
                                $result[Promotion::$scenes[Promotion::SCENES_ALL_SITE]]['price'] = $price;
                                $result[Promotion::$scenes[Promotion::SCENES_ALL_SITE]]['promotion'] = $item;
                            }
                            break;
                        case Promotion::DISCOUNT_UNIT_CURRENCY:
                            //这里暂时不处理获取单位转换;
                            $price = $product['real_price'] - $item['discount'];
                            if (empty($result[Promotion::$scenes[Promotion::SCENES_ALL_SITE]]['price']) or ($price <
                                    $result[Promotion::$scenes[Promotion::SCENES_ALL_SITE]]['price'])) {
                                $result[Promotion::$scenes[Promotion::SCENES_ALL_SITE]]['price'] = $price;
                                $result[Promotion::$scenes[Promotion::SCENES_ALL_SITE]]['promotion'] = $item;
                            }
                            break;
                    }
                } elseif ($item['scenes'] == Promotion::SCENES_FIRST_ORDER) {
                    switch ($item['discount_unit']) {
                        case Promotion::DISCOUNT_UNIT_PERCENT:
                            $price = $product['real_price'] * (1 - $item['discount'] / 100);
                            if (empty($result[Promotion::$scenes[Promotion::SCENES_FIRST_ORDER]]['price']) or ($price <
                                    $result[Promotion::$scenes[Promotion::SCENES_FIRST_ORDER]]['price'])) {
                                $result[Promotion::$scenes[Promotion::SCENES_FIRST_ORDER]]['price'] = $price;
                                $result[Promotion::$scenes[Promotion::SCENES_FIRST_ORDER]]['promotion'] = $item;
                            }
                            break;
                        case Promotion::DISCOUNT_UNIT_CURRENCY:
                            //这里暂时不处理获取单位转换;
                            $price = $product['real_price'] - $item['discount'];
                            if (empty($result[Promotion::$scenes[Promotion::SCENES_FIRST_ORDER]]['price']) or ($price <
                                    $result[Promotion::$scenes[Promotion::SCENES_FIRST_ORDER]]['price'])) {
                                $result[Promotion::$scenes[Promotion::SCENES_FIRST_ORDER]]['price'] = $price;
                                $result[Promotion::$scenes[Promotion::SCENES_FIRST_ORDER]]['promotion'] = $item;
                            }
                            break;
                    }
                } elseif ($item['keyword'] && (strstr($product['name'], $item['keyword']))) {
                    switch ($item['discount_unit']) {
                        case Promotion::DISCOUNT_UNIT_PERCENT:
                            $price = $product['real_price'] * (1 - $item['discount'] / 100);
                            if (empty($result[Promotion::$scenes[Promotion::SCENES_SPECIAL_PRODUCT]]['price']) or ($price <
                                    $result[Promotion::$scenes[Promotion::SCENES_SPECIAL_PRODUCT]]['price'])) {
                                $result[Promotion::$scenes[Promotion::SCENES_SPECIAL_PRODUCT]]['price'] = $price;
                                $result[Promotion::$scenes[Promotion::SCENES_SPECIAL_PRODUCT]]['promotion'] = $item;
                            }
                            break;
                        case Promotion::DISCOUNT_UNIT_CURRENCY:
                            //这里暂时不处理获取单位转换;
                            $price = $product['real_price'] - $item['discount'];
                            if (empty($result[Promotion::$scenes[Promotion::SCENES_SPECIAL_PRODUCT]]['price']) or ($price <
                                    $result[Promotion::$scenes[Promotion::SCENES_SPECIAL_PRODUCT]]['price'])) {
                                $result[Promotion::$scenes[Promotion::SCENES_SPECIAL_PRODUCT]]['price'] = $price;
                                $result[Promotion::$scenes[Promotion::SCENES_SPECIAL_PRODUCT]]['promotion'] = $item;
                            }
                            break;
                    }
                }
            }
        }
        //到这里判断出了最低价和最低价格的优惠信息，根据场景排序
        $result = collect($result)->sortBy('price')->sortBy('price')->toArray();


        return $result;
    }
}