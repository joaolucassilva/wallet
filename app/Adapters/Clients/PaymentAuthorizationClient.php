<?php

declare(strict_types=1);

namespace App\Adapters\Clients;

use GuzzleHttp\Client;

class PaymentAuthorizationClient
{
    public function __construct(
        private readonly Client $client,
    ) {
    }

    public function getClient(): Client
    {
        return $this->client;
    }
}
