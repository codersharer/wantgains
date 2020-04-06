<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\ProductRepository;
use App\Repositories\PromotionRepository;
use App\Services\Promotion\Handler;
use Illuminate\Http\Request;
use function compact;
use function json_encode;
use function route;
use function view;

class ProductController extends Controller
{
    public function show(Request $request)
    {
        $products = ProductRepository::getList(['products.id' => $request->get('product_id')]);
        $products = array_slice($products, ($page - 1) * $pageSize, $pageSize);
        $merchantIds = [];
        foreach ($products as $key => $product) {
            $merchantIds[] = $product['merchant_id'];
        }
        $merchantIds = array_unique($merchantIds);
        $promotions = PromotionRepository::getListByMerchantId($merchantIds);
        foreach ($products as $key => $product) {
            $promotions = Handler::handle($product, $promotions[$product['merchant_id']]) ?? [];
            $products[$key]['promotions'] = $promotions;
        }

        $output = view('include.ajax.product_detail', compact('products'))->render();

        return json_encode([
            'code' => 0,
            'html' => $output,
            'out'  => route('product.out', ['productId' => $products[0]['id']]),
        ]);
    }
}
