<?php

namespace App\Http\Controllers;

use App\Repositories\MerchantRepository;
use App\Repositories\ProductRepository;
use Illuminate\Http\Request;
use function route;
use Spatie\Crawler\Crawler;
use Spatie\Sitemap\SitemapGenerator;
use function compact;
use function dd;
use function view;

class MerchantController extends Controller
{
    /**
     * 商家促销详情页
     */
    public function show(Request $request, String $slug, int $merchantId)
    {
        $categories = MerchantRepository::getTopCategories(15);
        $merchantInfo = MerchantRepository::getInfoByField('id', $merchantId);
        $pageSize = 15;
        $page = $request->get('page', 1);
        $products = ProductRepository::getList(['merchant_id' => $merchantId], [
            'field'     => 'products.created_at',
            'sort_flag' => 'desc'
        ]);
        $totalPage = ceil(count($products) / $pageSize);
        $products = array_slice($products, ($page - 1) * $pageSize, $pageSize);
        if ($totalPage == $page) {
            $isLastPage = true;
        }
        if ($page == 1) {
            $isFirstPage = true;
        }
        $prevPage = $page - 1;
        $nextPage = $page + 1;
        $route = route('merchant.detail', ['slug' => $merchantInfo['slug'], 'merchant_id' => $merchantInfo['id']]);
        return view('merchant',
            compact('', 'categories', 'merchantInfo', 'products', 'isLastPage', 'isFirstPage', 'nextPage', 'prevPage',
                'page', 'route'));
    }


    public function index(Request $request, string $slug, int $merchantId)
    {
        //        $merchants = MerchantRepository::getList();

        return view('merchant', compact(''));

    }
}
