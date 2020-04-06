<?php

namespace App\Http\Controllers;

use App\Repositories\MerchantRepository;
use App\Repositories\ProductRepository;
use App\Repositories\PromotionRepository;
use App\Services\Promotion\Handler;
use function dd;
use Illuminate\Http\Request;
use function array_slice;
use function array_unique;
use function ceil;
use function compact;
use function count;
use function json_encode;
use function route;
use function var_dump;
use function view;

class IndexController extends Controller
{
    public function index(Request $request)
    {
        $isLastPage = false;
        $isFirstPage = false;
        $categories = MerchantRepository::getTopCategories(15);
        $products = ProductRepository::getTopTenPVProducts();
        $pageSize = 10;
        $totalPage = ceil(count($products) / $pageSize);
        $page = $request->get('page', 1);
        $products = array_slice($products, ($page - 1) * $pageSize, $pageSize);
        $merchantIds = [];
        foreach ($products as $key => $product) {
            $merchantIds[] = $product['merchant_id'];
        }
        $merchantIds = array_unique($merchantIds);
        $promotions = PromotionRepository::getListByMerchantId($merchantIds);
        foreach ($products as $key => $product) {
            $products[$key]['promotions']= Handler::handle($product, $promotions[$product['merchant_id']]) ?? [];
        }
        if ($totalPage == $page) {
            $isLastPage = true;
        }
        if ($page == 1) {
            $isFirstPage = true;
        }
        $prevPage = $page - 1;
        $nextPage = $page + 1;
        $route = route('index');
        return view('index',
            compact('categories', 'products', 'isLastPage', 'isFirstPage', 'page', 'prevPage', 'nextPage', 'route'));
    }
}
