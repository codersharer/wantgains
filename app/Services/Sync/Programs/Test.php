<?php


namespace App\Services\Sync\Programs;


class Test extends AbstractPrograms
{

    public function getPrograms()
    {
        $this->programs[] = [
            'name' => 'test1',
            'id_in_aff' => '1111',
            'homepage' => '1111',
            'domain' => '1.com',
            'default_track_link' => '1111.html',
            'real_track_link' => '1111.html',
        ];
        $this->programs[] = [
            'name' => 'test2',
            'id_in_aff' => '22222',
            'homepage' => '1111',
            'domain' => '2.com',
            'default_track_link' => '1111.html',
            'real_track_link' => '1111.html',
        ];

        $this->savePrograms();
    }

    public function getLinkFeeds()
    {

    }
}