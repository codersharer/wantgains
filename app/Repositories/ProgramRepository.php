<?php


namespace App\Repositories;


use App\Models\Program;

class ProgramRepository
{
    /**
     * 保存同步过来的program
     */
    public static function saveBySync($program)
    {
        $program = Program::updateOrCreate([
            'affiliate_id' => $program['affiliate_id'],
            'id_in_aff'    => $program['id_in_aff']
        ], $program);

        return $program;
    }

    /**
     * 获取本批次未更新到的program 列表
     *
     * @param $affId
     * @param $date
     */
    public static function getNotUpdateProgram($affId, $date)
    {
        $programs = Program::where(['affiliate_id' => $affId])->where('updated_at', '<=', $date)->get()->toArray();

        return $programs;
    }

    public static function getList($where = [], $orderBy = [])
    {
        if ($where) {
            $programs = Program::where($where);
        }
        if ($orderBy) {
            $programs->orderBy($orderBy['field'], $orderBy['sort_flag']);
        }
        $programs = $programs->get();
        if ($programs) {
            return $programs->toArray();
        }
        return [];
    }

    /**
     * 根据以联盟id分组查找某个domain
     *
     * @param       $domainId
     * @param array $where
     *
     * @return mixed
     */
    public static function getProgramsGroupByAffIdAndByDomainId($domainId, $where = [])
    {
        $programs = Program::where('domain_id',
            $domainId)->where($where)->with('affiliate')->groupBy('affiliate_id')->get()->toArray();

        return $programs;
    }
}