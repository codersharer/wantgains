<?php

namespace App\Console\Commands;

use App\Common\Http;
use App\Models\Merchant;
use App\Models\MerchantTraffic;
use App\Models\Program;
use App\Repositories\MerchantRepository;
use function array_keys;
use function array_values;
use function count;
use function dd;
use function explode;
use function feof;
use function fgetc;
use function fgetcsv;
use function fopen;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use function json_decode;
use function ksort;
use function preg_match;
use function storage_path;

/*
 * 从similarweb抓取商家流量数据
 */
class SyncTraffic extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:traffic';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $merchants = Program::where([
           'status_in_aff' => Program::STATUS_ACTIVE_IN_AFF,
           'status_in_dashboard' => Program::STATUS_ACTIVE_DASHBOARD,
        ])->leftJoin('merchants','merchants.id','=','programs.merchant_id')->select('merchant_id','merchants.domain')
            ->get()->toArray();
        foreach ($merchants as $merchant) {
            $traffic = MerchantTraffic::where('domain', $merchant['domain'])->first();
            if (empty($traffic))
                continue;
            if ($traffic->merchant_id)
                continue;
            MerchantTraffic::updateOrCreate([
                'domain' => $merchant['domain'],
            ], ['merchant_id' => $merchant['merchant_id']]);
            $this->cli->green("{$merchant['domain']} success");
        }
    }
}
