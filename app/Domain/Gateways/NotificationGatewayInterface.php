<?php

declare(strict_types=1);

namespace App\Domain\Gateways;

interface NotificationGatewayInterface
{
    public function send(array $data): void;
}
