<?php

namespace App\Console\Commands;

use App\Models\Merchant;
use function dd;
use GuzzleHttp\Client;
use GuzzleHttp\Pool;
use function addslashes;
use function preg_match;

class SyncTempDescription extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:temp-description';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '抓取描述';

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
        $page = 1;
        $pageSize = 50;
        while (true) {
            $merchants = Merchant::offset(($page - 1) * $pageSize)->limit($pageSize)->get()->toArray();
            if (empty($merchants)) {
                $this->cli->yellow("{$page} not found merchants");
                break;
            }
            $this->merchants = $merchants;
            $client = new Client();

            $requests = function () use ($merchants, $client) {
                foreach ($merchants as $merchant) {

                    $uri = $merchant['domain'];
                    yield function () use ($client, $uri) {
                        return $client->getAsync($uri, ['timeout' => 15]);
                    };
                }
            };

            $pool = new Pool($client, $requests($pageSize), [
                'concurrency' => 50,
                'fulfilled'   => function ($response, $index) {
                    $response = $response->getBody()->getContents();
                    preg_match('@<meta\s*name="description"\s*content="(?<description>.*?)"\s*/{0,1}>@ims', $response,
                        $matches);

                    if ($matches['description']) {
                        Merchant::where('id', $this->merchants[$index]['id'])->update([
                            'description' => addslashes($matches['description'])
                        ]);
                        $this->cli->green("{$this->merchants[$index]['slug']} success");
                    } else {
                        $this->cli->red("{$this->merchants[$index]['slug']} not found description");
                    }

                },
                'rejected'    => function ($reason, $index, $merchants) {
                    $this->cli->red("rejected");
                },
            ]);

            // 开始发送请求
            try {
                $promise = $pool->promise();
                $promise->wait();
            } catch (\Exception $exception) {

            }
            $this->cli->yellow("{$page} finish");
            $page++;
        }

    }
}
