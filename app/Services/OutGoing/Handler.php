<?php

namespace App\Services\OutGoing;

use App\Repositories\DomainRepository;
use App\Repositories\OutGoingRepository;
use function dd;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use function json_encode;
use League\CLImate\CLImate;

class Handler
{
    const OUTGOING_KEY = ":DOMAIN:";
    const PRODUCT_OUTGOING_KEY = ":PRODUCT:";

    protected $period;
    protected $strategy;
    protected $strategyObjects;
    protected $climate;
    protected $action;

    public function __construct($period = 0, $action = 'choice')
    {
        $this->period = $period;
        $this->climate = new CLImate();
        //这里设置策略，从上至下会依次执行选择最合适的出站
        $this->strategies = [
            AffiliateRankStrategy::class,
            WorestStrategy::class,
        ];
        $this->action = $action;
    }

    public function handle()
    {
        switch ($this->action) {
            case 'choice':
                $domains = DomainRepository::getUpdateByPeriod($this->period);
                if ($domains) {
                    foreach ($domains as $domain) {
                        $result = $this->useStrategy($domain);
                        if ($result === false) {
                            $this->climate->red("domain: {$domain['domain']} 未找到合适的出站联盟");
                        } else {
                            $this->climate->green("domain: {$domain['domain']} 找到合适的出站联盟");
                        }
                    }
                }
                break;
            case 'redis':
                $outgoings = OutGoingRepository::getByPeriod($this->period);
                if ($outgoings) {
                    foreach ($outgoings as $outgoing) {
                        $result = Cache::store('redis')->put(self::OUTGOING_KEY . $outgoing['domain'], json_encode($outgoing));
                        if ($result) {
                            $this->climate->green("{$outgoing['domain']} 刷入成功");
                        } else {
                            $this->climate->red("{$outgoing['domain']} 刷入失败");
                        }
                    }
                } else {
                    $this->climate->yellow('无可刷入redis数据');
                }
                break;
        }


        return true;
    }

    protected function useStrategy($domain): bool
    {
        $result = false;
        if (empty($this->strategyObjects)) {
            foreach ($this->strategies as $strategy) {
                $this->strategyObjects[] = new $strategy;
            }
        }

        foreach ($this->strategyObjects as $strategyObject) {
            if ($strategyObject->handle($domain)) {
                $result = true;
                break;
            }
        }

        return $result;
    }


}