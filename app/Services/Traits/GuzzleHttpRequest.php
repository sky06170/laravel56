<?php

namespace App\Services\Traits;

use GuzzleHttp\Client;

trait GuzzleHttpRequest
{
    private function sendRequest($method,$uri,$formParams, $headers = [])
    {
        $client = new Client();
        return $client->request($method,$uri,[
            'form_params' => $formParams,
            'headers' => $headers
        ]);
    }
}