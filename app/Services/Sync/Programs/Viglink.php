<?php


namespace App\Services\Sync\Programs;


use App\Common\Func;
use App\Common\Http;
use App\Exceptions\ProgramException;
use App\Models\Program;
use function json_decode;
use function preg_match;
use function urldecode;
use const PHP_EOL;

class Viglink extends AbstractPrograms
{
    public $apiKey = 'd3f9214f8ac891b16870fe7784223d48';
    protected $secretKey = 'd3cdfaf5b4b467b369631fd6938a9103771904e3';

    public function getPrograms()
    {
        $result = [];
        //获取商家列表
        $response = Http::get("https://publishers.viglink.com/api/merchant/search", [
            'Authorization' => "secret {$this->secretKey}",
        ]);

        $response = json_decode($response['content'], true);
        if (empty($response)) {
            throw new \Exception(ProgramException::EMPTY_PROGRAMS);
        }
        $totalPages = $response['totalPages'];
        if (empty($totalPages)) {
            throw new \Exception(ProgramException::EMPTY_PROGRAMS);
        }
        $page = $this->startPage;
        while (true) {
            $this->climate->yellow("page {$page} start" . PHP_EOL);
            $response = Http::get("https://publishers.viglink.com/api/merchant/search?page={$page}", [
                'Authorization' => "secret {$this->secretKey}",
            ]);
            $response = json_decode($response['content'], true);
            $programs = $response['merchants'];
            if ($programs) {
                foreach ($programs as $program) {

                    $this->climate->green("{$program['name']} find" . PHP_EOL);
                    $data['affiliate_id'] = $this->affInfo['id'];
                    $data['name'] = $program['name'];
                    if (empty($program['domains'][0])) {
                        $this->climate->green("{$program['name']} not found domain" . PHP_EOL);
                        continue;
                    }
                    $homepage = 'https://' . $program['domains'][0];
                    $data['homepage'] = urldecode($homepage);
                    $data['domain'] = Func::parseDomain($data['homepage']);
                    $data['id_in_aff'] = $program['groupId'];
                    if ($program['industryTypes']) {
                        foreach ($program['industryTypes'] as $industryType) {
                            $data['category'][] = $industryType['name'];
                        }
                        $data['category'] = implode(',', $data['category']);
                    }
                    $advertiseType = [];
                    if ($program['affiliateCPA'] == true) {
                        $advertiseType[] = 'cpa';
                    }
                    if ($program['affiliateCPC'] == true) {
                        $advertiseType[] = 'cpc';
                    }
                    $data['advertise_type'] = implode(',', $advertiseType);
                    $data['country'] = $program['countries'];
                    if ($data['domain']) {
                        $data['default_track_link'] = "https://redirect.viglink.com?key={$this->apiKey}&u={$homepage}";;
                        $data['real_track_link'] = "https://redirect.viglink.com?key={$this->apiKey}&u=[DEEPURL]&cuid=[SUBTRACKING]&opt=true";
                    }
                    $data['support_deep'] = Program::SUPPORT_DEEP;
                    $data['status_in_aff'] = $program['approved'] == true ? Program::STATUS_ACTIVE_IN_AFF : Program::STATUS_INACTIVE_IN_AFF;
                    $data['status_in_dashboard'] = $program['approved'] == true ? Program::STATUS_ACTIVE_DASHBOARD : Program::STATUS_INACTIVE_DASHBOARD;
                    if (empty($data['default_track_link'])) {
                        $data['status_in_aff'] = $data['status_in_dashboard'] = Program::STATUS_INACTIVE_DASHBOARD;
                    }
                    $data['description'] = '';
                    $data['commission_rate'] = "{$program['rates'][0]['min']}%-{$program['rates'][0]['max']}%";

                    $this->programs[] = $data;
                    $data = [];
                }
                $page++;
                $this->currentPage = $page;
                try {
                    $this->savePrograms();
                } catch (\Exception $exception) {
                    $this->climate->red($exception->getMessage());
                }
            }
            $this->climate->yellow("page {$page} finish" . PHP_EOL);
            if ($page > $totalPages) {
                break;
            }
        }
    }

    public function getLinkFeeds()
    {

    }

    protected function parseHomepage($url)
    {
        preg_match("@u=(?<homepage>.*?)&@ims", $url, $matches);

        return $matches;

    }

    public function getProducts()
    {
        //垃圾联盟，商品不准确，不接入
    }

}