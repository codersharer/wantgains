<?php

namespace App\Console\Commands;

use App\Models\Merchant;
use GuzzleHttp\Client;
use function date;
use function env;
use function file_exists;
use function pathinfo;
use function sleep;
use function time;

/**
 * 临时脚本，抓取商家logo
 *
 * Class SyncTempLogo
 * @package App\Console\Commands
 */
class SyncTempLogo extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:temp-logo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '抓取商家logo及描述';

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
        $pageSize = 10;
        $page = 1;
        $client = new Client(['verify' => false]);
        while (true) {
            $merchants = Merchant::where('id', '>=',
                1)->offset(($page - 1) * $pageSize)->orderBy('id')->limit($pageSize)->get()->toArray();
            if ($merchants) {
                foreach ($merchants as $merchant) {
                    $pathPng = '/Applications/MAMP/htdocs/wantgains/public/upload/merchant/logo/' . $merchant['slug'] . '.png';
                    $pathJpg = '/Applications/MAMP/htdocs/wantgains/public/upload/merchant/logo/' . $merchant['slug'] . '.jpg';
                    $pathJpeg = '/Applications/MAMP/htdocs/wantgains/public/upload/merchant/logo/' . $merchant['slug'] . '.jpeg';
                    if (file_exists($pathPng)) {
                        Merchant::where('id', $merchant['id'])->update([
                            'logo'       => '/upload/merchant/logo/' . $merchant['slug'] . '.png',
                            'updated_at' => date('Y-m-d H:i:s', time())
                        ]);
                        continue;
                    } elseif (file_exists($pathJpg)) {
                        Merchant::where('id', $merchant['id'])->update([
                            'logo'       => '/upload/merchant/logo/' . $merchant['slug'] . '.jpg',
                            'updated_at' => date('Y-m-d H:i:s', time())
                        ]);
                        continue;
                    } elseif (file_exists($pathJpeg)) {
                        Merchant::where('id', $merchant['id'])->update([
                            'logo'       => '/upload/merchant/logo/' . $merchant['slug'] . '.jpeg',
                            'updated_at' => date('Y-m-d H:i:s', time())
                        ]);
                        continue;
                    }


                    $url = "https://www.retailmenot.com/thumbs/logos/l/{$merchant['domain']}-coupons.jpg";

                    if ($url) {
                        $pathInfo = pathinfo($url);
                        $path = env('APP_IMAGE_MERCHANT_PATH') . 'merchant/logo/' . $merchant['slug'] . '.' . $pathInfo['extension'];
                        $absolutePath = __DIR__ . '/../../../public' . $path;
                        try {
                            $client->get($url, ['save_to' => $absolutePath]);
                        } catch (\Exception $exception) {
                            $this->cli->red("{$merchant['slug']} not found image");
                            continue;
                        }
                        $logo = $path;
                    }
                    Merchant::where('id', $merchant['id'])->update([
                        'logo' => $logo,
                    ]);
                    $this->cli->green("{$merchant['id']} {$merchant['slug']} success");
                    sleep(10);
                }
            } else {
                break;
            }
            $page++;
        }
    }
}
