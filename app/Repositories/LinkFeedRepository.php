<?php


namespace App\Repositories;


use App\Models\Promotion;

class LinkFeedRepository
{
    /**
     * 保存同步过来的linkfeed
     */
    public static function saveBySync($link)
    {
        $link = Promotion::updateOrCreate([
            'affiliate_id' => $link['affiliate_id'],
            'link_id'    => $link['link_id'],
        ], $link);

        return $link;
    }

}