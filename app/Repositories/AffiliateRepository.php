<?php


namespace App\Repositories;


use App\Models\Affiliate;

class AffiliateRepository
{
    public static function getInfoById($affId)
    {
        $result = [];

        if ($affId) {
            $result = Affiliate::find($affId);
        }

        return $result;
    }

    public static function getInfoByName($name)
    {
        $result = [];

        if ($name) {
            $result = Affiliate::where(['name' => $name])->first()->toArray();
        }

        return $result;
    }
}