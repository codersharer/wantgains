<?php


namespace App\Services\Sync\Programs;


use App\Models\Product;
use App\Models\Program;
use App\Models\Promotion;
use App\Repositories\CrawleLogRepository;
use App\Repositories\DomainRepository;
use App\Repositories\LinkFeedRepository;
use App\Repositories\MerchantRepository;
use App\Repositories\ProductRepository;
use App\Repositories\ProgramRepository;
use App\Services\Sync\Validate\ProgramValidate;
use App\Services\Validate\LinkFeedValidate;
use App\Services\Validate\ProductValidate;
use function dd;
use function html_entity_decode;
use Illuminate\Support\Facades\DB;
use League\CLImate\CLImate;
use function count;
use function date;
use function explode;
use function stripslashes;
use function strtolower;
use const PHP_EOL;

abstract class AbstractPrograms
{
    //联盟信息
    protected $affInfo;
    //设置异常programs;
    protected $exceptPrograms = [];
    //programs
    protected $programs = [];
    //links
    protected $links = [];
    //linkfeed
    protected $linkFeeds = [];
    //products
    protected $products = [];
    //每批次处理量
    protected $limit = 100;
    //命令行样式
    protected $climate;
    //总页数
    protected $totalPages;
    //抓取起始时间
    protected $startAt;
    //抓取类型
    protected $type;
    //续抓起始页码
    protected $startPage = 1;
    //续抓当前页码
    protected $currentPage = 1;
    //续抓当前programid
    protected $currentProgramId;
    //日志id
    protected $logId;
    //下限超数报警
    protected $offlineNumber = 10;
    //下线product超数报警
    protected $offlineProductNumber = 1000;

    public function __construct($affInfo)
    {
        $this->affInfo = $affInfo;
        $this->climate = new CLImate();
        $this->startAt = date('Y-m-d H:i:s');
    }

    /**
     * 获取programs和linfeed
     *
     * @return mixed
     */
    abstract public function getPrograms();

    abstract public function getLinkFeeds();

    abstract public function getProducts();

    protected function savePrograms()
    {
        foreach ($this->programs as $program) {
            if (!ProgramValidate::handle($program)) {
                //如果验证必填字段验证失败则默认为下线状态
                $program['status_in_dashboard'] = Program::STATUS_INACTIVE_DASHBOARD;
            }
            try {
                //开启事物，现在暂时获取一条保存一条
                DB::transaction(function () use ($program) {
                    //保存domain信息
                    $program['domain'] = strtolower($program['domain']);
                    $domainId = DomainRepository::saveBySync($program);
                    $program['domain_id'] = $domainId;
                    //保存商家信息
                    $merchantName = explode('.', $program['name']);
                    $program['merchant_name'] = ucfirst($merchantName[0]);
                    $merchantId = MerchantRepository::save($program);
                    $program['merchant_id'] = $merchantId;

                    //保存program主信息
                    $program['affiliate_id'] = $this->affInfo['id'];
                    $program['updated_at'] = date('Y-m-d H:i:s');
                    $program['country'] = $program['country'] ?? "us,uk";
                    ProgramRepository::saveBySync($program);

                    //保存商家对应分类信息
                    $categoryData['merchant_id'] = $merchantId;
                    $categoryData['categories'] = explode(',', $program['category']);
                    MerchantRepository::saveCategory($categoryData);
                });
            } catch (\Exception $exception) {
                echo $exception->getMessage() . PHP_EOL;
                continue;
            }

            echo "{$program['domain']} finish" . PHP_EOL;
        }

        //保存抓取日志记录
        $logData['id'] = $this->logId;
        $logData['current_page'] = $this->currentPage;
        CrawleLogRepository::save($logData);

    }

    protected function saveLinkFeeds()
    {
        foreach ($this->links as $link) {
            if (!LinkFeedValidate::handle($link)) {
                //如果验证必填字段验证失败则默认为下线状态
                $link['status'] = Promotion::INACTIVE_STATUS;
            }
            //开启事物，现在暂时获取一条保存一条
            DB::transaction(function () use ($link) {
                //保存link信息
                LinkFeedRepository::saveBySync($link);
            });
            $this->climate->green("{$link['name']} finish" . PHP_EOL);
        }
    }

    protected function saveProducts()
    {
        foreach ($this->products as $product) {
            if (!ProductValidate::handle($product)) {
                //如果验证必填字段验证失败则默认为下线状态
                $product['status'] = Promotion::INACTIVE_STATUS;
            }
            //开启事物，现在暂时获取一条保存一条
            DB::transaction(function () use ($product) {
                //保存link信息
                $merchant = MerchantRepository::getInfoByField('domain', $product['domain']);
                $product['updated_at'] = date('Y-m-d H:i:s');
                $product['merchant_id'] = $merchant['id'];
                $product['name'] = html_entity_decode(stripslashes(trim($product['name'])));
                $product['source'] = 'affiliate';
                ProductRepository::saveBySync($product);
            });
            $this->climate->green("{$product['name']} finish" . PHP_EOL);
        }
        //保存抓取日志记录
        $logData['id'] = $this->logId;
        $logData['current_page'] = $this->currentPage;
        $logData['current_program_id'] = $this->currentProgramId;
        CrawleLogRepository::save($logData);
    }

    public function setOffline()
    {
        //设置未更新到program的为下线
        DB::transaction(function () {
            $programs = ProgramRepository::getNotUpdateProgram($this->affInfo['id'], $this->startAt);
            if (count($programs) > $this->offlineNumber) {
                throw new \Exception('Offline too much, please check');
            }
            foreach ($programs as $program) {
                $program['updated_at'] = date('Y-m-d H:i:s');
                $program['status_in_dashboard'] = Program::STATUS_INACTIVE_DASHBOARD;
                ProgramRepository::saveBySync($program);
            }
        });
    }

    public function setOfflineProducts()
    {
        //设置未更新到program的为下线
        DB::transaction(function () {
            $products = ProductRepository::getNotUpdateProgram($this->affInfo['id'], $this->startAt);
            if (count($products) > $this->offlineProductNumber) {
                throw new \Exception('Offline too much, please check');
            }
            foreach ($products as $product) {
                $product['updated_at'] = date('Y-m-d H:i:s');
                $product['status'] = Product::STATUS_INACTIVE;
                ProductRepository::saveBySync($product);
            }
        });
    }

    public function setCurrentpage($page)
    {
        $this->currentPage = $page;
    }

    public function setStartPage($page)
    {
        $this->startPage = $page;
    }

    public function setLogId($id)
    {
        $this->logId = $id;
    }

    public function getLogId()
    {
        return $this->logId;
    }

    public function setStartAt($date)
    {
        $this->startAt = $date;
    }

    public function setCurrentProgramId($programId)
    {
        $this->currentProgramId = $programId;
    }


}