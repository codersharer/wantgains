<?php

namespace App\Console\Commands;

use App\Models\OutGoing;
use App\Repositories\OutGoingRepository;
use function dd;
use Illuminate\Console\Command;
use function json_decode;
use function sleep;

class SyncOutTracking extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:out_tracking';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '实时解析出站数据入库';

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
            $line = $redis->lpop(OutGoing::OUTGOING_KEY);
            if (empty($line)) {
                sleep(10);
                continue;
            }
            $line = json_decode($line, true);
            OutGoingRepository::saveTracking($line);
        }
    }
}
