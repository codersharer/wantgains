<?php

namespace App\Common;

use function count;
use function explode;
use function json_decode;
use function json_encode;
use function libxml_use_internal_errors;
use function simplexml_load_string;

class Func
{
    /**
     * 获取domain
     *
     * @param $url
     *
     * @return mixed
     */
    public static function parseDomain($url): ?string
    {
        $replace = array(
            'https://www.',
            'http://www.',
            'https://',
            'http://',
            'www.'
        );
        $domain = str_replace($replace, '', $url);
        $delimiters = array('/', '?', '#');
        foreach ($delimiters as $delimiter) {
            if (strpos($domain, $delimiter)) {
                $arr = explode($delimiter, $domain);
                $domain = $arr[0];
                break;
            }
        }
        return $domain;
    }


    /**
     * xml转json
     *
     * @param $data
     *
     * @return mixed
     */
    public static function xmlToJson($data, $test = false)
    {
        libxml_use_internal_errors(true);
        $data = simplexml_load_string($data);
        $data = json_encode($data);
        return json_decode($data, true);
    }

    /**
     * 解析出顶级域名
     *
     * @param $domain
     */
    public static function parseDomainName($domain)
    {
        if (empty($domain)) {
            return '';
        }

        $explode = explode('.', $domain);
        if (count($explode) == 2) {
            return $explode[0];
        } elseif (count($explode) == 3) {
            return $explode[1];
        }
    }
}