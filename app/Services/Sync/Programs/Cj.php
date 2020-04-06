<?php


namespace App\Services\Sync\Programs;


use App\Common\Func;
use App\Common\Http;
use App\Models\Program;
use App\Models\Promotion;
use App\Repositories\ProgramRepository;
use function addslashes;
use function array_slice;
use function ceil;
use function count;
use function dd;
use function explode;
use function html_entity_decode;
use function implode;
use function is_array;
use function md5;
use const PHP_EOL;
use function str_replace;

class Cj extends AbstractPrograms
{
    protected $token = '3t7t7x45ybn353v0v74ye9e9sy';
    protected $customerId = 5397286;
    protected $websiteId = 9267025;
    protected $advertiserUrl;
    protected $trackUrl;
    protected $productUrl;
    protected $offlineNumber = 20;
    protected $offlineProductNumber = 500000;

    public function __construct($affInfo)
    {
        parent::__construct($affInfo);
        $this->advertiserUrl = "https://advertiser-lookup.api.cj.com/v2/advertiser-lookup?requestor-cid={$this->customerId}&advertiser-ids=joined";
        $this->trackUrl = "https://link-search.api.cj.com/v2/link-search?website-id={$this->websiteId}&link-type=Text Link&advertiser-ids=";
        $this->productUrl = "https://product-search.api.cj.com/v2/product-search?website-id={$this->websiteId}&advertiser-ids={advertiserId}";
    }

    public function getPrograms()
    {
        //每页抓取条数
        $perPage = 25;
        $response = Http::get($this->advertiserUrl, ['Authorization' => "Bearer {$this->token}"]);
        $response = Func::xmlToJson($response['content']);
        $this->totalPages = ceil($response['advertisers']['@attributes']['total-matched'] / $perPage);
        for ($i = $this->startPage; $i <= $this->totalPages; $i++) {
//            $this->climate->yellow("page : {$i} start");
            echo "page {$i} start" . PHP_EOL;
            $this->currentPage = $i;
            $response = Http::get($this->advertiserUrl . "&page-number={$i}",
                ['Authorization' => "Bearer {$this->token}"]);
            $response = Func::xmlToJson($response['content']);
            $programs = $response['advertisers']['advertiser'];
            if ($programs) {
                foreach ($programs as $program) {
                    //保存advertiser-id用以获取tracklink
                    $data = [];
                    $data['name'] = $program['advertiser-name'];
                    $data['status_in_aff'] = Program::STATUS_ACTIVE_IN_AFF;
                    $data['status_in_dashboard'] = Program::STATUS_ACTIVE_DASHBOARD;
                    $data['seven_day_epc'] = $program['seven-day-epc'];
                    $data['three_month_epc'] = $program['three-month-epc'];
                    $data['homepage'] = $program['program-url'];
                    $data['id_in_aff'] = $program['advertiser-id'];
                    $data['support_deep'] = Program::SUPPORT_DEEP;

                    if (empty($data['id_in_aff'])) {
                        $data['status_in_dashboard'] = Program::STATUS_INACTIVE_DASHBOARD;
                    }
                    if ($program['account-status'] != 'Active') {
                        $data['status_in_aff'] = Program::STATUS_INACTIVE_IN_AFF;
                    }
                    $data['domain'] = Func::parseDomain($data['homepage']);
                    if ($program['primary-category']) {
                        $categories = [];
                        if ($program['primary-category']['parent']) {
                            $parentCategories = explode('/', $program['primary-category']['parent']);
                            foreach ($parentCategories as $parentCategory) {
                                $categories[] = $parentCategory;
                            }
                        }
                        if ($program['primary-category']['child']) {
                            $childCategories = explode('/', $program['primary-category']['child']);
                            foreach ($childCategories as $childCategory) {
                                $categories[] = $childCategory;
                            }
                        }
                        $data['category'] = implode(',', $categories);
                    }
                    if (count($program['actions']['action']) > 1) {
                        foreach ($program['actions'] as $action) {
                            $data['commission_rate'] .= $action['name'] . ':' . $action['commission']['default'] . PHP_EOL;
                        }
                    } else {
                        $data['commission_rate'] = $program['actions']['action']['name'] . ':' . $program['actions']['action']['commission']['default'];
                    }

                    //获取tracklink
                    $response = Http::get("https://link-search.api.cj.com/v2/link-search?website-id={$this->websiteId}&link-type=banner&advertiser-ids={$program['advertiser-id']}",
                        ['Authorization' => "Bearer {$this->token}"]);
                    $response = Func::xmlToJson($response['content']);
                    $links = $response['links']['link'];
                    if ($links) {
                        $data['default_track_link'] = $links[0]['clickUrl'];
                        if ($data['default_track_link']) {
                            $data['real_track_link'] = $data['default_track_link'] . '?sid=[SUBTRACKING]';
                        }
                    } else {
                        $data['status_in_aff'] = Program::STATUS_INACTIVE_IN_AFF;
                        $data['status_in_dashboard'] = Program::STATUS_INACTIVE_DASHBOARD;
                        $data['default_track_link'] = '';
                        $data['real_track_link'] = '';
                    }

                    $data['updated_at'] = date('Y-m-d H:i:s');
                    $this->programs[$data['id_in_aff']] = $data;
                }
            }
            $this->savePrograms();
            $this->programs = [];
        }

    }

    public function getLinkFeeds()
    {

    }

    public function getProducts()
    {
        $where = [
            'affiliate_id'        => $this->affInfo['id'],
            'status_in_aff'       => Program::STATUS_ACTIVE_IN_AFF,
            'status_in_dashboard' => Program::STATUS_ACTIVE_DASHBOARD
        ];
        if ($this->currentProgramId) {
            $where[] = ['id', '>=', $this->currentProgramId];
        }
        $programs = ProgramRepository::getList($where, ['field' => 'id', 'sort_flag' => 'asc']);
        //        dd($this->currentPage, $this->startPage, $this->currentProgramId,$this->logId);
        if ($programs) {
            foreach ($programs as $program) {
                $this->currentProgramId = $program['id'];
                //获取link
                $perPage = 300;
                $url = str_replace("{advertiserId}", $program['id_in_aff'],
                        $this->productUrl) . '&records-per-page=' . $perPage;
                $response = Http::get($url, ['Authorization' => "Bearer {$this->token}"]);
                $response = Func::xmlToJson($response['content']);
                if (!$response['products']['@attributes']['total-matched']) {
                    continue;
                }
                $totalPage = (int)ceil($response['products']['@attributes']['total-matched'] / $perPage);
                $this->climate->green("total page: {$totalPage}");
                for ($i = $this->startPage; $i <= $totalPage; $i++) {
                    //必须设置，用于断点续抓
                    $this->currentPage = $i;
                    $this->climate->yellow("programId : {$this->currentProgramId} page: {$i} start");
                    $url = str_replace("{advertiserId}", $program['id_in_aff'],
                            $this->productUrl) . '&records-per-page=' . $perPage . '&page-number=' . $i;
                    $response = Http::get($url, ['Authorization' => "Bearer {$this->token}"]);
                    $response = Func::xmlToJson($response['content'], true);
                    $products = $response['products']['product'];
                    if (empty($products)) {
                        $this->climate->red("program:{$program['id_in_aff']} page : {$i} products parse error maybe");
                        break;
                    }
                    if (!is_array($products[0]) && $products) {
                        $tmpProduct = $products;
                        unset($products);
                        $products[] = $tmpProduct;
                    }
                    foreach ($products as $product) {
                        $categories = '';
                        if ($product['advertiser-category']) {
                            $categories = explode('>', $product['advertiser-category']);
                            foreach ($categories as $key => $value) {
                                $categories[$key] = $value;
                            }
                            $categories = implode(',', $categories);
                        }
                        $realPrice = $product['price'];
                        if ($product['sale-price']) {
                            $realPrice = $product['sale-price'];
                        }
                        $sku = '';
                        if ($product['manufacturer-sku']) {
                            $sku = $product['manufacturer-sku'];
                        }
                        $this->products[] = [
                            'product_id_in_aff' => md5($product['name']),
                            'affiliate_id'      => $this->affInfo['id'],
                            'id_in_aff'         => $program['id_in_aff'],
                            'domain_id'         => $program['domain_id'],
                            'domain'            => $program['domain'],
                            'name'              => addslashes($product['name']),
                            'category'          => $categories,
                            'description'       => $product['description'] ? addslashes($product['description']) : '',
                            'track_link'        => html_entity_decode($product['buy-url']) . '&sid=[SUBTRACKING]',
                            'image_url'         => $product['image-url'] ?? '',
                            'price'             => $product['price'],
                            'real_price'        => $realPrice,
                            'status'            => Promotion::ACTIVE_STATUS,
                            'sku'               => $sku,
                        ];
                    }
                    $this->saveProducts();
                    $this->products = [];
                }
            }
        }
    }

}