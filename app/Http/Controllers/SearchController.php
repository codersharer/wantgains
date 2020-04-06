<?php

namespace App\Http\Controllers;

use App\Common\Http;
use App\Repositories\MerchantRepository;
use App\Repositories\PromotionRepository;
use App\Services\Promotion\Handler;
use Illuminate\Http\Request;
use function compact;
use function json_decode;
use function json_encode;
use function view;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->get('q');
        //查找搜索相关的商家
        $merchants = MerchantRepository::getList([
            ['name', 'like', "%{$q}%"]
        ]);

        $pageSize = 10;
        $page = $request->get('page', 1);
        $isLastPage = false;
        $isFirstPage = false;
        $products = [];
        $query = [
            'query' => [
                'match' => [
                    'name' => ['query' => $q, 'operator' => 'and'],
                ],
            ],
            'sort'  => [
                'real_price' => 'asc',
            ]
        ];
        $uri = "localhost:9200/products/product/_search?size={$pageSize}&from=" . ($page - 1) * $pageSize;
        $response = Http::get($uri, [
            'Content-Type' => 'application/json',
        ], '', json_encode($query));
        $response = json_decode($response['content'], true);
        $total = $response['hits']['total']['value'] ?? 0;
        if ($total) {
            $totalPage = ceil($total / $pageSize);
            foreach ($response['hits']['hits'] as $hit) {
                $products[] = $hit['_source'];
            }
        }
        $merchantIds = [];
        if ($products) {
            foreach ($products as $key => $product) {
                $merchantIds[] = $product['merchant_id'];
            }
            $merchantIds = array_unique($merchantIds);
            $promotions = PromotionRepository::getListByMerchantId($merchantIds);
            foreach ($products as $key => $product) {
                $products[$key]['promotions'] = Handler::handle($product, $promotions[$product['merchant_id']]);
            }
        }


        if ($totalPage == $page) {
            $isLastPage = true;
        }
        if ($page == 1) {
            $isFirstPage = true;
        }
        $prevPage = $page - 1;
        $nextPage = $page + 1;
        $route = route('search', 'q=' . $q);
        return view('search',
            compact('isLastPage', 'isFirstPage', 'products', 'categories', 'nextPage', 'prevPage', 'route', 'q',
                'merchants'));
    }
}
