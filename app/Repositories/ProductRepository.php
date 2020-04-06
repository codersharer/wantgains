<?php


namespace App\Repositories;


use App\Models\Affiliate;
use App\Models\Product;
use App\Models\Program;
use function array_slice;
use function dd;

class ProductRepository
{
    /**
     * 保存同步过来的product
     */
    public static function saveBySync($product)
    {
        $product = Product::updateOrCreate([
            'affiliate_id'      => $product['affiliate_id'],
            'product_id_in_aff' => $product['product_id_in_aff']
        ], $product);

        return $product;
    }

    public static function getList($where = [], $orderBy = [], $take = 100)
    {
        $products = Product::where($where);

        $products->leftJoin('merchants', 'products.domain_id', '=', 'merchants.domain_id');
        $products->select('products.id', 'products.image_url', 'products.price', 'products.real_price', 'products.name',
            'products.id as product_id', 'products.image_url as product_image', 'products.track_link',
            'merchants.slug as merchant_slug', 'merchants.id as merchant_id', 'merchants.name as merchant_name',
            'products.created_at')->take($take);
        if ($orderBy) {
            $products->orderBy($orderBy['field'], $orderBy['sort_flag']);
        } else {
            //            $products->inRandomOrder();
        }
        $products = $products->get()->toArray();

        return $products;

    }

    /**
     * 首页默认商品展示
     *
     * @param $categories
     */
    public static function getTopProducts($categories)
    {
        $result = [];
        //根据分类反推分类下非兜底联盟的商家
        foreach ($categories as $category) {
            $merchants = Program::where([
                'category'            => $category->category,
                'status_in_aff'       => Program::STATUS_ACTIVE_IN_AFF,
                'status_in_dashboard' => Program::STATUS_ACTIVE_DASHBOARD,
            ])->leftJoin('merchant_traffics', 'merchant_traffics.merchant_id', '=',
                'programs.merchant_id')->orderBy('pv', 'desc')->get();
            if (empty($merchants)) {
                continue;
            }
            $merchants = $merchants->toArray();
            //取分类下流量前10商家
            $merchants = array_slice($merchants, 0, 10);
            //获取商家下商品
            foreach ($merchants as $merchant) {
                $products = Product::where('merchant_id', $merchant['merchant_id'])->get()->toArray();
                if (empty($products)) {
                    continue;
                }
                dd($products);

            }
        }

        return $result;
    }

    public static function getProductsByMerchantIds($merchantIds, $take = 100)
    {

        $products = Product::whereIn('merchant_id', $merchantIds)->where([
            'status' => Product::STATUS_ACTIVE,
        ])->leftJoin('merchants', 'merchants.id', '=',
            'products.merchant_id')->select('products.id', 'products.image_url', 'products.name', 'merchants.slug as 
            merchant_slug', 'merchants.id as merchant_id', 'merchants.name as 
            merchant_name', 'products.description', 'products.price', 'products.real_price', 'products.track_link',
            'products.created_at')->take($take)->get()->toArray();

        return $products;
    }

    /**
     * 获取存在联盟关系非兜底联盟(viglink)前10流量的商家
     */
    public static function getTopTenPVProducts($categories = [])
    {
        $products = [];
        //首先获取存在接入关系的top10pv商家id
        $merchants = Program::leftJoin('merchant_traffics', 'merchant_traffics.merchant_id', '=',
            'programs.merchant_id')->where([
            'status_in_aff'       => Program::STATUS_ACTIVE_IN_AFF,
            'status_in_dashboard' => Program::STATUS_ACTIVE_IN_AFF,
        ])->whereNotIn('affiliate_id', Affiliate::$worestIds)->groupBy('programs.merchant_id')->orderBy('pv',
            'desc')->get()->toArray();
        //根据获取到的商家取products， 各取5条
        $productNumber = 0;
        foreach ($merchants as $merchant) {
            if ($productNumber >= 50) {
                break;
            }
            if (empty($merchant['merchant_id'])) {
                continue;
            }
            $list = Product::where([
                'merchant_id' => $merchant['merchant_id'],
                ['price', '>', 0],
                'status' => Product::STATUS_ACTIVE
            ])->leftJoin('merchants', 'merchants.id', '=', 'products.merchant_id')->select('products.*',
                'merchants.name as merchant_name', 'merchants.slug as merchant_slug')->take(6)->orderBy('created_at',
                'desc')->get()->toArray();
            if (empty($list)) {
                continue;
            }
            $productNumber += count($list);
            $products = array_merge($products, $list);
        }

        return $products;

    }

    public static function getInfo($field, $value)
    {
        if (empty($field) or empty($value)) {
            return [];
        }

        $info = Product::where([$field => $value])->first()->toArray();

        return $info;
    }

    public static function getNotUpdateProgram($affId, $date)
    {
        $products = Product::where(['affiliate_id' => $affId])->where('updated_at', '<=', $date)->get()->toArray();
        return $products;

    }
}
