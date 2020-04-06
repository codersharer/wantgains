<?php


namespace App\Repositories;


use App\Models\CrawleLog;

class CrawleLogRepository
{
    /**
     * 获取最新的一条联盟未完成的抓取记录
     *
     * @param $affId
     */
    public static function getInfo($where = [], $orderBy = [])
    {
        $info = CrawleLog::where($where);
        if ($orderBy) {
            $info->orderBy($orderBy['field'], $orderBy['sort_flag']);
        }
        $info = $info->first();
        if ($info) {
            return $info->toArray();
        }

        return [];
    }

    public static function save($data)
    {
        return CrawleLog::updateOrCreate(['id' => $data['id']], $data);
    }
}