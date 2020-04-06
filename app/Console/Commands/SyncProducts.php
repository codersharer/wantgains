<?php

namespace App\Console\Commands;

use App\Services\Sync\Programs\Handler;
use Exception;

class SyncProducts extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:products {--affid= : 联盟id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '同步联盟products';

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
        try {
            //获取联盟id
            $affId = $this->option('affid');
            if (empty($affId)) {
                throw new Exception('请输入需要同步的联盟id');
            }
            $handler = new Handler($affId);
            $handler->handle("products");

        } catch (\Throwable $exception) {
            $this->cli->error($exception->getFile() . '   ' . $exception->getLine() . '  ' . $exception->getMessage());
        }


    }
}
