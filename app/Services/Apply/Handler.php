<?php


namespace App\Services\Apply;


use App\Repositories\AffiliateRepository;
use Exception;
use function dd;
use function strtolower;
use function ucfirst;

class Handler
{
    protected $affId;

    public function __construct($affId)
    {
        $this->affId = $affId;
    }

    public function handle()
    {
        $affInfo = AffiliateRepository::getInfoById($this->affId);
        if (empty($affInfo)) {
            throw new Exception("联盟：{$this->affId}不存在或已被删除");
        }
        //统一首字母大写其余小写
        $className = ucfirst(strtolower($affInfo->name));
        $namespace = __NAMESPACE__ . '\\' . $className;
        $object = new $namespace($affInfo);
        $object->handle();
    }
}