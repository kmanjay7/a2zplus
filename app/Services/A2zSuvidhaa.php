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
        $params = array_merge([
            'token' => 'dc2dcd636fb7f9dd2a041b2e419743ef',
            'agentid' => env('A2Z_USERID'),
            'outletid' => 14702,
        ], $params);

        $response = Http::post($uri, $params);

        return $response;
    }
}