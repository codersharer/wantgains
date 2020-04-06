<?php

namespace App\Console\Commands;

use App\Services\Apply\Handler;
use Exception;
use Spatie\Sitemap\Sitemap;

class ApplyPrograms extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'apply:programs {--affid= : 联盟id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '批量申请合作关系';

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
        Sitemap::create()->add('/merchant/1')->writeToFile('sitemap.xml');

        try {
            //获取联盟id
            $affId = $this->option('affid');
            if (empty($affId)) {
                throw new Exception('请输入需要同步的联盟id');
            }
            $handler = new Handler($affId);
            $handler->handle();

        } catch (\Throwable $exception) {
            $this->cli->error($exception->getMessage());
        }

    }
}
