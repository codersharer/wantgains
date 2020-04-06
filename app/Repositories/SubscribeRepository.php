<?php


namespace App\Repositories;


use App\Models\Subscribe;
use function date;
use Illuminate\Support\Facades\DB;
use function time;

class SubscribeRepository
{
    public static function save($data)
    {
        DB::transaction(function() use ($data) {
            //先删除后新增
            Subscribe::where('mail', $data['mail'])->delete();
            if ($data['keywords']) {
                foreach ($data['keywords'] as $keyword) {
                    Subscribe::insert([
                        'mail' => $data['mail'],
                        'keyword' => $keyword,
                        'updated_at' => date('Y-m-d H:i:s', time()),
                        'created_at' => date('Y-m-d H:i:s', time()),
                    ]);
                }
            } else {
                Subscribe::insert([
                    'mail' => $data['mail'],
                    'keyword' => '',
                    'updated_at' => date('Y-m-d H:i:s', time()),
                    'created_at' => date('Y-m-d H:i:s', time()),
                ]);
            }
        });

        return true;
    }

}