<?php

namespace App\Console\Commands;

use App\Models\MerchantSubscribeCheck;
use App\Models\Program;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use function date;
use function stristr;
use function strlen;
use function time;

class SyncTempCheckCanSubscribe extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:can-subscribe {--affiliate_id=:}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '检测商家是否支持订阅';

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
        $client = new Client([
            'timeout'         => 30,
            'connect_timeout' => 10,
        ]);
        $keywords = ['SUBSCRIBE', 'NEWSLETTER', 'voucher'];
        $page = 1;
        $pageSize = 100;
        $programs = Program::where('affiliate_id',
            $this->option('affiliate_id'))->groupBy('merchant_id')->get()->toArray();
        $requests = function () use ($programs) {
            foreach ($programs as $program) {
                yield new Request('GET', $program['domain']);
            }
        };

        $pool = new Pool($client, $requests(), [
            'concurrency' => 30,
            'fulfilled'   => function (Response $response, $index) use ($keywords, $programs) {
                $html = $response->getBody()->getContents();
                $findKeywords = ',';
                foreach ($keywords as $keyword) {
                    if (stristr($html, $keyword)) {
                        $findKeywords .= $keyword . ',';
                    }
                }
                //大于1说明找到关键词了
                if (strlen($findKeywords) > 1) {
                    MerchantSubscribeCheck::updateOrCreate(['merchant_id' => $programs[$index]['id']], [
                        'domain'                 => $programs[$index]['domain'],
                        'domain_id'              => $programs[$index]['domain_id'],
                        'estimate_can_subscribe' => 1,
                        'subscribe_keyword'      => $findKeywords,
                        'http_code'              => $response->getStatusCode(),
                        'created_at'             => date('Y-m-d H:i:s', time()),
                        'updated_at'             => date('Y-m-d H:i:s', time()),
                    ]);
                    $this->cli->green("{$programs[$index]['domain']} may be can subscribe");
                } else {
                    $this->cli->red("{$programs[$index]['domain']} may be can't subscribe");
                }

            },
            'rejected'    => function (RequestException $reason, $index) {
            },
        ]);
        $promise = $pool->promise();
        $promise->wait();
        $this->cli->green('check subscribe finish');
    }
}
