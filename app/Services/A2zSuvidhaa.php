<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class A2zSuvidhaa
{
    public static function getResponse(string $uri = null, array $params = [])
    {
        $url = env('A2Z_URL');

        $url .= $uri;

        $params = array_merge([
            'userId' => env('A2Z_USERID'),
            'api_token' => env('A2Z_TOKEN'),
            'secretKey' => env('A2Z_SECRET_KEY'),
        ], $params);

        $response = Http::post($url, $params);

        return $response;
    }

    public static function instantPayResponse(string $uri = null, array $params = [])
    {
        $url = env('INSTANTPAY_URL');

        $url .= $uri;

        $params = [
            'token' => env('INSTANTPAY_TOKEN'),
            'request' => array_merge([
                'agentid' => env('INSTANTPAY_AGENTID'),
                'outletid' => env('INSTANTPAY_OUTLETID')
            ], $params)
        ];

        $response = Http::post($url, $params);

        return $response;
    }
}