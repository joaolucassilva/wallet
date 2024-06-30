<?php

declare(strict_types=1);

namespace App\Adapters\Database\Repositories\EloquentORM;

use App\Domain\Database\Repositories\TransferRepositoryInterface;
use App\Domain\Entities\TransferEntity;
use App\Models\Transfer;

class TransferRepository implements TransferRepositoryInterface
{
    public function __construct(
        private readonly Transfer $transfer
    ) {
    }

    public function create(TransferEntity $entity): void
    {
        $payerWalletId = $entity->getPayerWallet()->getId();
        $payeeWalletId = $entity->getPayeeWallet()->getId();

        $this->transfer
            ->newQuery()
            ->create([
                'uuid' => $entity->getUuid(),
                'payer_wallet_id' => $payerWalletId,
                'payee_wallet_id' => $payeeWalletId,
                'amount' => $entity->getAmount()->getAmountInCents(),
                'type' => $entity->getType()->value,
                'created_at' => $entity->getCreatedAt(),
                'updated_at' => $entity->getUpdatedAt(),
            ]);
    }
}
