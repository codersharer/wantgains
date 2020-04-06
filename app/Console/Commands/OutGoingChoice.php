<?php

namespace App\Console\Commands;

use App\Services\OutGoing\Handler;

class OutGoingChoice extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'outgoing:choice {--all} {--period=:}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '出站选择，选项--all全量设置，反之取前--period小时有更新的domain';

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
        $isAll = $this->option('all');
        $period = $this->option('period');
        if ($isAll) {
            $period = 0;
        } else {
            $period = (int)$period == 0 ? 1 : (int)$period;
        }
        $outgoing = new Handler($period);
        $outgoing->handle();
        $this->cli->green("出站选择执行成功");
    }
}
