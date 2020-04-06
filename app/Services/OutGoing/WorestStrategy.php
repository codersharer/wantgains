<?php


namespace App\Services\OutGoing;


use App\Models\OutGoing;
use App\Repositories\AffiliateRepository;
use App\Services\Sync\Programs\Viglink;
use function date;
use function time;

/**
 * 兜底策略，走viglink的anywhere
 *
 * Class WorestStrategy
 * @package App\Services\OutGoing
 */
class WorestStrategy extends AbstractStrategy
{

    public function handle($domain) : bool
    {
        $affiliate = AffiliateRepository::getInfoByName('viglink');
        $viglink = new Viglink([]);
        $data = [
            'affiliate_id'   => $affiliate['id'],
            'affiliate_name' => $affiliate['name'],
            'domain_id'      => $domain['id'],
            'domain'         => $domain['domain'],
            'program_id'     => 0,
            'track_link'     => "https://redirect.viglink.com?key={$viglink->apiKey}&u=[DEEPURL]&cuid=[SUBTRACKING]&opt=true",
            'is_handle'      => OutGoing::NOT_HANDLE,
            'updated_at'     => date('Y-m-d H:i:s', time()),
        ];

        OutGoing::updateOrCreate(['domain_id' => $data['domain_id']], $data);

        return true;
        //        OutGoingRepository::save($program);

    }
}