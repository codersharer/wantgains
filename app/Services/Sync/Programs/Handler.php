<?php


namespace App\Services\Sync\Programs;


use App\Models\CrawleLog;
use App\Repositories\AffiliateRepository;
use App\Repositories\CrawleLogRepository;
use function date;
use Exception;
use function dd;
use function strtolower;
use function time;
use function ucfirst;

class Handler
{
    protected $affId;

    public function __construct($affId)
    {
        $this->affId = $affId;
    }

    public function handle($type = 'programs')
    {
        $affInfo = AffiliateRepository::getInfoById($this->affId);
        if (empty($affInfo)) {
            throw new Exception("联盟：{$this->affId}不存在或已被删除");
        }
        //统一首字母大写其余小写
        $className = ucfirst(strtolower($affInfo->name));
        $namespace = __NAMESPACE__ . '\\' . $className;
        $object = new $namespace($affInfo);
        $this->initCrawleLog($object, $type);
        switch ($type) {
            case "programs":
                $object->getPrograms();
                $object->setOffline();
                break;
            case "linkfeed":
                $object->getLinkFeeds();
                break;
            case "products":
                $object->getProducts();
                $object->setOfflineProducts();
                break;
        }
        $this->finishCrawleLog($object);
    }

    public function initCrawleLog($object, $type)
    {
        $crawleLog = CrawleLogRepository::getInfo([
            'affiliate_id' => $this->affId,
            'type'         => $type,
        ], ['field' => 'started_at', 'sort_flag' => 'desc']);

        //之前已经完成抓取，重新开启抓取日志
        if (($crawleLog['is_finish'] == CrawleLog::FINISH) or (empty($crawleLog))) {
            $object->setCurrentpage(1);
            $object->setStartPage(1);
            $data['started_at'] = date('Y-m-d H:i:s', time());
            $data['affiliate_id'] = $this->affId;
            $data['type'] = $type;
            $crawleLog = CrawleLogRepository::save($data);
        } else {
            $object->setCurrentpage($crawleLog['current_page']);
            $object->setStartPage($crawleLog['current_page']);
            switch ($type) {
                case 'products':
                    $object->setCurrentProgramId($crawleLog['current_program_id']);
                    break;
            }
            $object->setStartAt($crawleLog['started_at']);
        }
        $object->setLogId($crawleLog['id']);
    }
    public function finishCrawleLog($object)
    {
        $data['id'] = $object->getLogId();
        $data['is_finish'] = CrawleLog::FINISH;
        $data['finished_at'] = date('Y-m-d H:i:s', time());
        CrawleLogRepository::save($data);
    }

}