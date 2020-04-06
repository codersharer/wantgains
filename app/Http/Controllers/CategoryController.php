<?php

namespace App\Http\Controllers;

use App\Repositories\MerchantRepository;
use App\Repositories\ProductRepository;
use App\Repositories\PromotionRepository;
use App\Services\Promotion\Handler;
use function array_slice;
use function compact;
use function dd;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use function view;

class CategoryController extends Controller
{

    public function index(Request $request, string $category)
    {
        $categories = MerchantRepository::getTopCategories(10);
        $topMerchants = MerchantRepository::getTopMerchantByCategory($category);
        //先取前6商家
        $topMerchants = array_slice($topMerchants, 0, 6);
        foreach ($topMerchants as $merchant) {
            if (empty($merchant['merchant_id']))
                continue;
            $merchantIds[] = $merchant['merchant_id'];
        }
        $products = ProductRepository::getProductsByMerchantIds($merchantIds);
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
            $products[$key]['promotions'] = Handler::handle($product, $promotions[$product['merchant_id']]);
        }
        if ($totalPage == $page) {
            $isLastPage = true;
        }
        if ($page == 1) {
            $isFirstPage = true;
        }
        $prevPage = $page - 1;
        $nextPage = $page + 1;
        $route = route('category', ['category' => $category]);

        return view('category', compact('products', 'categories', 'isFirstPage', 'isLastPage', 'page', 'nextPage', 'prevPage', 'route', 'category','topMerchants'));
    }

    /**
     * 分类下对应的商家
     *
     */
    public function merchants(Request $request, string $category)
    {
        $categories = MerchantRepository::getCategories(10);

        $merchants = MerchantRepository::getMerchantByCategory($category, 10);

        return view('category_merchants', compact('merchants', 'categories', 'category'));
    }
}
