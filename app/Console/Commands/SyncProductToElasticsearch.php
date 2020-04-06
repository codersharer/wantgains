<?php

namespace App\Console\Commands;

use App\Models\Product;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use function count;
use function json_encode;

class SyncProductToElasticsearch extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:product-elasticsearch {--all} {--period=:} {--active} {--inactive}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '同步product到elasticsearch';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if ($this->option('active')) {
            $this->active();
        } elseif ($this->option('inactive')) {
            $this->inActive();
        } else {
            $this->cli->yellow('请选择操作模式');
        }
    }

    /**
     * 状态为在线的入库
     *
     */
    public function active()
    {
        $page = 1;
        $pageSize = 100;
        $client = new Client();
        while (true) {
            $products = Product::leftJoin('merchants', 'merchants.id', '=',
                'products.merchant_id')->select('products.*', 'merchants.name as merchant_name',
                'merchants.slug as merchant_slug')->where('products.status',
                Product::STATUS_ACTIVE)->offset(($page - 1) * $pageSize)->limit($pageSize)->get()->toArray();
            if (empty($products)) {
                break;
            }
            $count = count($products);
            $requests = function ($count) use ($products) {
                foreach ($products as $product) {
                    $uri = 'http://localhost:9200/products/product/' . $product['id'];
                    yield new Request('PUT', $uri, [
                        'Content-Type' => 'application/json',
                    ], json_encode($product));
                }
            };

            $pool = new Pool($client, $requests($count), [
                'concurrency' => 50,
                'fulfilled'   => function (Response $response, $index) {
                    // this is delivered each successful response
                    //                    dd('success');
                },
                'rejected'    => function (RequestException $reason, $index) {
                    // this is delivered each failed request
                },
            ]);

            // Initiate the transfers and create a promise
            $promise = $pool->promise();

            // Force the pool of requests to complete.
            $promise->wait();
            $page++;
            $this->cli->yellow("{$page} success");
        }
        $this->cli->green("finish");
    }


    /**
     * 状态为下线的入库
     *
     */
    public function inActive()
    {
        $page = 1;
        $pageSize = 100;
        $client = new Client();
        while (true) {
            $products = Product::leftJoin('merchants', 'merchants.id', '=',
                'products.merchant_id')->select('products.*', 'merchants.name as merchant_name',
                'merchants.slug as merchant_slug')->where('products.status',
                Product::STATUS_INACTIVE)->offset(($page - 1) * $pageSize)->limit($pageSize)->get()->toArray();
            if (empty($products)) {
                break;
            }
            $count = count($products);
            $requests = function ($count) use ($products) {
                foreach ($products as $product) {
                    $uri = 'http://localhost:9200/products/product/' . $product['id'];
                    yield new Request('DELETE', $uri, [
                        'Content-Type' => 'application/json',
                    ]);
                }
            };

            $pool = new Pool($client, $requests($count), [
                'concurrency' => 50,
                'fulfilled'   => function (Response $response, $index) {
                    // this is delivered each successful response
                    //                    dd('success');
                },
                'rejected'    => function (RequestException $reason, $index) {
                    // this is delivered each failed request
                },
            ]);

            // Initiate the transfers and create a promise
            $promise = $pool->promise();

            // Force the pool of requests to complete.
            $promise->wait();
            $page++;
            $this->cli->yellow("{$page} success");
        }
        $this->cli->green("finish");
    }
}
