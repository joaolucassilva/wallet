<?php

declare(strict_types=1);

namespace App\Domain\Entities;

use App\Domain\Enums\TransferTypeEnum;
use App\Domain\ValueObjects\Money;
use App\Domain\ValueObjects\UUID;
use DateTimeImmutable;

class TransferEntity
{
    public function __construct(
        private readonly WalletEntity $payerWallet,
        private readonly WalletEntity $payeeWallet,
        private readonly Money $amount,
        private readonly TransferTypeEnum $type,
        private ?Uuid $uuid = null,
        private ?DateTimeImmutable $createdAt = null,
        private ?DateTimeImmutable $updatedAt = null,
    ) {
        $this->uuid = !is_null($this->uuid) ?: UUID::generate();
        $this->createdAt = !is_null($this->createdAt) ?: new DateTimeImmutable();
        $this->updatedAt = !is_null($this->updatedAt) ?: new DateTimeImmutable();
    }

    public function getUuid(): UUID
    {
        return $this->uuid;
    }

    public function getPayerWallet(): WalletEntity
    {
        return $this->payerWallet;
    }

    public function getPayeeWallet(): WalletEntity
    {
        return $this->payeeWallet;
    }

    public function getAmount(): Money
    {
        return $this->amount;
    }

    public function getStatus(): TransferTypeEnum
    {
        return $this->type;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeImmutable
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
