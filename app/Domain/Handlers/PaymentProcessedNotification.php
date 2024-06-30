<?php

declare(strict_types=1);

namespace App\Domain\Handlers;

use App\Domain\Events\PaymentProcessed;
use App\Domain\Gateways\NotificationGatewayInterface;

readonly class PaymentProcessedNotification
{
    public function __construct(
        private NotificationGatewayInterface $notificationGateway,
    ) {
    }

    public function handle(PaymentProcessed $event): void
    {
        $user = $event->wallet->getUser();

        $this->notificationGateway
            ->send([
                'email' => $user->getEmail(),
                'phone' => $user->getPhone(),
            ]);
    }
}
