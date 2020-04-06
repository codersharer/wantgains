<?php

namespace App\Console\Commands;

use App\Repositories\MerchantRepository;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;

class GenerateSiteMap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:sitemap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '生成网站sitemap';

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
        $merchants = MerchantRepository::getList();
        $sitemap = Sitemap::create();
        $sitemap->add('/');
        foreach ($merchants as $merchant) {
            $sitemap->add('/merchant/' . $merchant['slug']);
        }
        $sitemap->writeToFile('public/sitemap.xml');
    }
}
