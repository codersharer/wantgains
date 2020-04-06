<?php


namespace App\Repositories;


use App\Models\OutGoing;
use App\Models\OutGoingTracking;
use function date;
use function time;

class OutGoingRepository
{
    public static function save($info)
    {
        if ($info) {
            $data = [
                'affiliate_id'   => $info['affiliate']['id'],
                'affiliate_name' => $info['affiliate']['name'],
                'domain_id'      => $info['domain_id'],
                'domain'         => $info['domain'],
                'program_id'     => $info['id'],
                'track_link'     => $info['real_track_link'],
                'is_handle'      => OutGoing::NOT_HANDLE,
                'updated_at'     => date('Y-m-d H:i:s', time()),
            ];

            OutGoing::updateOrCreate(['domain_id' => $data['domain_id']], $data);
        }

        return;
    }

    public static function getByPeriod($period = 0)
    {
        if ($period) {
            $updatedAt = time() - ($period * 3600);
            $outgoings = OutGoing::where('updated_at', '>=', date('Y-m-d H:i:s', $updatedAt))->get()->toArray();
        } else {
            $outgoings = self::getList();
        }

        return $outgoings;
    }

    public static function getList($where = [])
    {
        if ($where) {
            $outgoings = OutGoing::where($where)->get()->toArray();
        } else {
            $outgoings = OutGoing::get()->toArray();
        }

        return $outgoings;
    }

    public static function saveTracking($data)
    {
        OutGoingTracking::create([
            'domain_id' => $data['domain_id'],
            'program_id' => $data['program_id'] ?? 0,
            'affiliate_id' => $data['affiliate_id'],
            'track_link' => $data['track_link'],
            'merchant_id' => $data['merchant_id'],
            'product_id' => $data['product_id'] ?? 0,
            'ip' => $data['ip'],
            'user_agent' => $data['user_agent'],
            'sid' => $data['sid'],
            'type' => $data['type'],
        ]);


        return true;
    }
}