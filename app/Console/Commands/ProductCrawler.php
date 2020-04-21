<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use function json_encode;
use QL\QueryList;
use function dd;

class ProductCrawler extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawler:products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '抓取商家product list';

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
        //        $rules = [
        //            'img' => ['.product-image','src'],
        //            'title' => ['.product-item-link', 'title'],
        //            'url' => ['.product-item-link', 'href'],
        //            'is_sold_out' => ['.vert>.text', 'text'],
        //        ];
        //        $range = '.product-items .product-item';
        //        $ql = QueryList::get('https://doctorsbestweightloss.com/collections/chocolate-lovers')->rules($rules)->range($range)->query()->getData();
        //        dd($ql->all());

        //        $rules = [
        //            'img' => ['.phone-img','data-src'],
        //            'title' => ['h3', 'text'],
        //            'url' => ['.plp_container>a', 'href'],
        //        ];
        //        $range = '.main-results .plplist';
        //        $ql = QueryList::get('https://shop.tracfone.com/shop/en/tracfonestore/deals', '',[
        //            'headers' => [
        //                'user-agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.163 Safari/537.36',
        //            ]
        //        ])->rules($rules)->range
        //        ($range)
        //            ->query()->getData();
        //        dd($ql->all());

        $ql = QueryList::postJson('https://productcatalogueapi.jabra.com//v1/search/groups?include=available',
            [
                'top' => 12,
                'marketLocale' => 'en-us',
                'filter' => "attributes/any(a: a eq 'Product_Portfolio|Jabra') and attributes/all(a: a ne 'Meta_Producttype|8') and attributes/all(a: a ne 'Meta_Producttype|2') and (familyId ne 421) and (familyId ne 340)  and (isDiscounted eq true) and (availabilityState lt 3)",
            ],
            [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],

            ]);
        dd($ql->getHtml());

    }
}
