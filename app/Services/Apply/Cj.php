<?php


namespace App\Services\Apply;


use App\Common\Func;
use App\Common\Http;
use function dd;
use function json_decode;
use function sleep;
use function strstr;
use const PHP_EOL;

class Cj extends AbstractProgramsApply
{
    protected $token = '6a1xd92qx1rsh9t49gqvme6qag';
    protected $customerId = 5397286;
    protected $websiteId = 9267025;
    protected $advertiserUrl;

    public function __construct($affInfo)
    {
        parent::__construct($affInfo);
        $this->cookies['data'] = [
            'JSESSIONID'          => 'abcP0PgPPweBT9ubwnadx',
            'AuthenticationToken' => 'd7ae0bf9-fb07-42d6-bef9-98286e0248b8',
        ];
        $this->cookies['domain'] = 'members.cj.com';
        //只申请英国和美国
        $this->advertiserUrl = "https://members.cj.com/member/publisher/5397286/advertiserSearch.json?publisherId=5397286&pageSize=50&advertiserCountries=US%2CGB&geographicSource=&relationshipStatus=none&sortColumn=advertiserName&sortDescending=false";
    }

    public function handle()
    {
        $perPage = 50;
        $response = Http::get($this->advertiserUrl, [], $this->cookies);
        $response = json_decode($response['content'], true);
        $totalPages = (int)ceil($response['totalResults'] / $perPage);
        for ($i = 1; $i <= $totalPages; $i++) {
            $this->climate->yellow("page {$i} start" . PHP_EOL);
            $response = Http::get($this->advertiserUrl . "&pageNumber={$i}",[], $this->cookies);
            $response = json_decode($response['content'], true);
            $programs = $response['advertisers'];
            if ($programs) {
                foreach ($programs as $key => $program) {
                    //1分钟只能调用25次，防止请求被拒绝
                    if ($key % 10 == 0) {
                        sleep(5);
                    }
                    $url = "https://members.cj.com/member/accounts/publisher/affiliations/joinprograms.do?onJoin=clickSearch&advertiserId={$program['advertiserId']}&publisherId={$this->customerId}&norefresh=true";
                    $response = Http::get($url, [], $this->cookies);
                    if (strstr($response['content'], "广告商会联系你")) {
                        $this->climate->green("{$program['advertiserId']} apply success" . PHP_EOL);
                    }
                }
            }

            $this->climate->yellow("page {$i} finish" . PHP_EOL);
        }
    }
}