<?php

declare(strict_types=1);

namespace App\Adapters\Gateways;

use App\Adapters\Clients\NotifyClient;
use App\Domain\Gateways\NotificationGatewayInterface;
use Exception;
use GuzzleHttp\Exception\GuzzleException;

class NotificationGateway implements NotificationGatewayInterface
{
    public function __construct(
        private readonly NotifyClient $notifyClient,
    ) {
    }

    public function send(array $data): void
    {
        try {
            $this->notifyClient
                ->getClient()
                ->post(
                    uri: config('services.notify.url'),
                    options: [
                        'json' => $data
                    ],
                );
        } catch (Exception|GuzzleException $e) {
            report($e);
        }
    }
}
