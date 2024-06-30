<?php

declare(strict_types=1);

namespace App\Domain\Events;

use App\Domain\Entities\WalletEntity;

class PaymentProcessed
{
    public function __construct(
        public WalletEntity $wallet,
    ) {
    }
}
