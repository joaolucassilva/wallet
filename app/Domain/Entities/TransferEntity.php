<?php

declare(strict_types=1);

namespace App\Domain\Entities;

use App\Domain\Enums\TransferTypeEnum;
use App\Domain\ValueObjects\Money;
use App\Domain\ValueObjects\UUID;
use DateTimeImmutable;

class TransferEntity
{
    private DateTimeImmutable $createdAt;
    private DateTimeImmutable $updatedAt;

    public function __construct(
        private readonly WalletEntity $payerWallet,
        private readonly WalletEntity $payeeWallet,
        private readonly Money $amount,
        private readonly TransferTypeEnum $type,
        private Uuid|string $uuid = '',
    ) {
        if ($this->uuid === '') {
            $this->uuid = Uuid::generate();
        }

        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = new DateTimeImmutable();
    }

    public function getIdentifier(): UUID
    {
        return $this->uuid;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public static function createOutgoing(WalletEntity $payer, WalletEntity $payee, Money $amount): TransferEntity
    {
        return new TransferEntity(
            payerWallet: $payer,
            payeeWallet: $payee,
            amount: $amount,
            type: TransferTypeEnum::OUTGOING,
        );
    }

    public static function createIncoming(WalletEntity $payer, WalletEntity $payee, Money $amount): TransferEntity
    {
        return new TransferEntity(
            payerWallet: $payer,
            payeeWallet: $payee,
            amount: $amount,
            type: TransferTypeEnum::INCOMING,
        );
    }
}
