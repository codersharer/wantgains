<?php


namespace App\Services\OutGoing;


use App\Models\Program;
use App\Repositories\OutGoingRepository;
use App\Repositories\ProgramRepository;
use function krsort;

/**
 * 根据联盟权重策略选择合适的出站
 *
 * Class AffiliateRankStrategy
 * @package App\Services\OutGoing
 */
class AffiliateRankStrategy extends AbstractStrategy
{
    public function handle($domain): bool
    {
        $tempPrograms = [];
        $programs = ProgramRepository::getProgramsGroupByAffIdAndByDomainId($domain['id'], [
            'status_in_aff'       => Program::STATUS_ACTIVE_IN_AFF,
            'status_in_dashboard' => Program::STATUS_ACTIVE_DASHBOARD,
        ]);
        if ($programs) {
            foreach ($programs as $program) {
                //这里目前就简单的根据联盟权重排序，尽量避免出现重复weight的联盟
                $tempPrograms[$program['affiliate']['weight']] = $program;
            }
            krsort($tempPrograms, SORT_NUMERIC);
            //获取第一条weight最高的
            $program = array_shift($tempPrograms);
            OutGoingRepository::save($program);
            return true;
        }

        return false;


    }
}