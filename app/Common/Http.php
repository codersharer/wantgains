<?php

namespace App\Common;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\TransferStats;

class Http
{
    public static function get($url, $headers = [], $cookies = [], $body = '')
    {
        $client = new Client();
        $effectiveUri = '';
        try {
            $cookieJar = '';
            if ($cookies) {
                $cookieJar = CookieJar::fromArray($cookies['data'], $cookies['domain']);
            }
            $response = $client->get($url, [
                'allow_redirects' => [
                    'max'             => 10,        // allow at most 10 redirects.
                    'strict'          => true,      // use "strict" RFC compliant redirects.
                    'referer'         => true,      // add a Referer header
                    'protocols'       => ['https'], // only allow https URLs
                    'track_redirects' => true
                ],
                'on_stats'        => function (TransferStats $stats) use (&$effectiveUri) {
                    $effectiveUri = $stats->getEffectiveUri();
                },
                'timeout'         => 60,
                'headers'         => $headers,
                'cookies'         => $cookieJar,
                'body'            => $body,
            ]);


            return [
                'content'       => $response->getBody()->getContents(),
                'code'          => $response->getStatusCode(),
                'effective_uri' => $effectiveUri,
            ];
        } catch (GuzzleException $exception) {

            return [
                'content'       => '',
                'code'          => $exception->getCode(),
                'effective_uri' => '',
            ];
        }

    }

    public static function post($url, $data = [], $headers = [], $cookies = '', $extra = [])
    {
        $client = new Client();
        $effectiveUri = '';
        try {
            $cookieJar = '';
            if ($cookies) {
                $cookieJar = CookieJar::fromArray($cookies['data'], $cookies['domain']);
            }
            $response = $client->post($url, [
                'allow_redirects' => [
                    'max'             => 10,        // allow at most 10 redirects.
                    'strict'          => true,      // use "strict" RFC compliant redirects.
                    'referer'         => true,      // add a Referer header
                    'protocols'       => ['https'], // only allow https URLs
                    'track_redirects' => true
                ],
                'on_stats'        => function (TransferStats $stats) use (&$effectiveUri) {
                    $effectiveUri = $stats->getEffectiveUri();
                },
                'timeout'         => 60,
                'headers'         => $headers,
                'cookies'         => $cookieJar,
                'form_params'     => $data,
            ]);


            return [
                'content'       => $response->getBody()->getContents(),
                'code'          => $response->getStatusCode(),
                'effective_uri' => $effectiveUri,
            ];
        } catch (GuzzleException $exception) {

            return [
                'content'       => '',
                'code'          => $exception->getCode(),
                'effective_uri' => '',
            ];
        }

    }
}