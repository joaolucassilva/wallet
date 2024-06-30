<?php

declare(strict_types=1);

namespace App\Domain\Database\Repositories;

use App\Domain\Entities\WalletEntity;

interface WalletRepositoryInterface
{
    public function findByUserId(int $userId): WalletEntity;

    public function updateBalance(WalletEntity $walletEntity): void;
}
