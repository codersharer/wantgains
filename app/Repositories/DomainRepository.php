<?php


namespace App\Repositories;


use App\Common\Func;
use App\Models\Domain;
use function date;
use function dd;
use function time;

class DomainRepository
{
    /**
     * 保存同步过来的domain
     *
     * @param $data
     */
    public static function saveBySync($program)
    {
        if (empty($program['domain'])) {
            $program['domain'] = Func::parseDomain($program['homepage']);
        }
        if (empty($program['domain'])) {
            return 0;
        }

        //这里更新时间主要是为了定时任务可以获取最新的有变动的domain，来重新选择出站
        $domain = Domain::updateOrCreate(['domain' => $program['domain']], ['updated_at' => date('Y-m-d H:i:s')]);
        return $domain->id;
    }

    public static function getList($where = [])
    {
        if ($where) {
            $domains = Domain::where($where)->get()->toArray();
        } else {
            $domains = Domain::get()->toArray();

        }

        return $domains;
    }

    /**
     * 获取前$period更新过的domain
     *
     * @param int $period
     */
    public static function getUpdateByPeriod($period = 0)
    {
        $domains = [];
        if ($period) {
            $updatedAt = time() - ($period * 3600);
            $domains = Domain::where('updated_at', '>=', date('Y-m-d H:i:s', $updatedAt))->get();
            if ($domains) {
                $domains = $domains->toArray();
            }
        } else {
            $domains = self::getList();
        }

        return $domains;
    }

    public static function getInfoByDomain($domain)
    {
        $info =  Domain::where('domain', $domain)->first();
        if ($info) {
            return $info->toArray();
        }

        return [];
    }
}