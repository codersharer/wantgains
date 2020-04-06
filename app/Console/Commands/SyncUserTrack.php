<?php

namespace App\Console\Commands;

use App\Models\UserTrack;
use function date;
use function dd;
use Illuminate\Console\Command;
use function json_decode;
use function sleep;
use function time;

class SyncUserTrack extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:user-track';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '从redis消费用户行为记录';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $redis = app('redis.connection');
        while (true) {
            $line = $redis->lpop(UserTrack::REDIS_KEY);
            $line = json_decode($line, true);
            if (empty($line)) {
                sleep(5);
                continue;
            }
            $line['updated_at'] = date('Y-m-d H:i:s', time());
            $line['created_at'] = date('Y-m-d H:i:s', time());
            try {
                UserTrack::insert($line);
            } catch (\Exception $exception) {
                //以后记录日志
                echo $exception->getMessage();
            }
        }
    }

}
