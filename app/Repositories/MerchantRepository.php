<?php


namespace App\Repositories;


use App\Models\Affiliate;
use App\Models\Merchant;
use App\Models\MerchantCategory;
use App\Models\MerchantTraffic;
use App\Models\Program;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use function date;
use function dd;
use function is_array;
use function time;

class MerchantRepository
{
    public static function save($data)
    {
        $merchant = Merchant::updateOrCreate(['domain_id' => $data['domain_id']], [
            'name'       => $data['merchant_name'],
            'domain_id'  => $data['domain_id'],
            'domain'     => $data['domain'],
            'slug'       => Str::slug($data['merchant_name']),
            'updated_at' => date('Y-m-d H:i:s', time()),
        ]);

        return $merchant->id;
    }

    public static function saveCategory($data)
    {
        if (is_array($data['categories'])) {
            foreach ($data['categories'] as $category) {
                MerchantCategory::updateOrCreate([
                    'merchant_id' => $data['merchant_id'],
                    'category'    => $category,
                ], ['merchant_id' => $data['merchant_id'], 'category' => $category]);
            }
        } else {
            MerchantCategory::updateOrCreate([
                'merchant_id' => $data['merchant_id'],
                'category'    => $data['categories'],
            ], ['merchant_id' => $data['merchant_id'], 'category' => $data['categories']]);
        }


        return true;
    }

    public static function saveTraffic($data)
    {
        MerchantTraffic::updateOrCreate([
            'domain' => $data['domain'],
        ], $data);

    }

    public static function getList($where=[])
    {
        if ($where) {
            $merchants = Merchant::where($where)->get()->toArray();
        } else {
            $merchants = Merchant::get()->toArray();
        }

        return $merchants;
    }

    public static function getInfoByField($field, $value)
    {
        $info = Merchant::where($field, $value)->first()->toArray();

        return $info;
    }

    public static function getTopCategories($take = 10)
    {
        $categories = DB::table('merchant_categories')->select(DB::raw('count(1) as count, category'))->where('category',
            '!=', '')->where('category', '!=', 'Other')->groupBy('category')->orderBy('count',
            'desc')->take($take)->get();
        if ($categories) {
            return $categories->toArray();
        }

        return [];
    }

    public static function getTopMerchantByCategory($category = '', $take = 1000)
    {
        if (empty($category)) {
            return [];
        }

        $merchants = Program::whereNotIn('programs.affiliate_id',
            Affiliate::$worestIds)->where('merchant_categories.category', $category)->leftJoin('merchants',
                'merchants.id', '=', 'programs.merchant_id')->leftJoin('merchant_traffics',
                'merchant_traffics.merchant_id', '=', 'programs.merchant_id')->leftJoin('merchant_categories',
                'merchant_categories.merchant_id', '=',
                'programs.merchant_id')->select('merchants.name as merchant_name', 'merchants.slug as merchant_slug',
                'merchants.id as merchant_id', 'merchants.logo as merchant_logo',
                'merchants.domain as merchant_domain')->orderBy('pv',
                'desc')->groupBy('programs.merchant_id')->take($take)->get()->toArray();

        if (empty($merchants)) {
            return [];
        }
        //把有商品的商家列出来
        foreach ($merchants as $merchant) {
            $merchantIds[] = $merchant['merchant_id'];
        }
        $merchants = Merchant::whereIn('merchants.id', $merchantIds)->leftJoin('products',
            'products.merchant_id', '=', 'merchants.id')->select([
                DB::raw('COUNT(products.merchant_id) as count'),
                'merchants.name as merchant_name',
                'merchants.slug as merchant_slug',
                'merchants.id as merchant_id',
                'merchants.logo as merchant_logo',
                'merchants.domain as merchant_domain',
            ])->groupBy('merchant_id')->orderBy('count', 'desc')->get()->toArray();

        return $merchants;
    }


}