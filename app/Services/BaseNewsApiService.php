<?php

namespace App\Services;

use GuzzleHttp\Client;

class BaseNewsApiService
{
    protected Client $client;

    public function __construct()
    {
        $this->client = new Client();
    }
}