<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Entities;

use App\Domain\Entities\TransferEntity;
use App\Domain\Entities\WalletEntity;
use App\Domain\Enums\TransferTypeEnum;
use App\Domain\ValueObjects\Money;
use App\Domain\ValueObjects\UUID;
use DateTimeImmutable;
use Mockery;
use PHPUnit\Framework\TestCase;

class TransferEntityTest extends TestCase
{
    public function test_create_outgoing_transfer(): void
    {
        $walletMock = Mockery::mock(WalletEntity::class);
        $transfer = TransferEntity::createOutgoing(
            payer: $walletMock,
            payee: $walletMock,
            amount: new Money(10000),
        );

        $this->assertSame(TransferTypeEnum::OUTGOING, $transfer->getType());
        $this->assertInstanceOf(UUID::class, $transfer->getUuid());
        $this->assertSame((new Money(10000))->getAmountInCents(), $transfer->getAmount()->getAmountInCents());
        $this->assertInstanceOf(DateTimeImmutable::class, $transfer->getCreatedAt());
        $this->assertInstanceOf(DateTimeImmutable::class, $transfer->getUpdatedAt());
        $this->assertInstanceOf(WalletEntity::class, $transfer->getPayerWallet());
        $this->assertInstanceOf(WalletEntity::class, $transfer->getPayeeWallet());
    }

    public function test_create_incoming_transfer(): void
    {
        $walletMock = Mockery::mock(WalletEntity::class);
        $transfer = TransferEntity::createIncoming(
            payer: $walletMock,
            payee: $walletMock,
            amount: new Money(10000),
        );

        $this->assertSame(TransferTypeEnum::INCOMING, $transfer->getType());
        $this->assertInstanceOf(UUID::class, $transfer->getUuid());
        $this->assertSame((new Money(10000))->getAmountInCents(), $transfer->getAmount()->getAmountInCents());
        $this->assertInstanceOf(DateTimeImmutable::class, $transfer->getCreatedAt());
        $this->assertInstanceOf(DateTimeImmutable::class, $transfer->getUpdatedAt());
        $this->assertInstanceOf(WalletEntity::class, $transfer->getPayerWallet());
        $this->assertInstanceOf(WalletEntity::class, $transfer->getPayeeWallet());
    }
}
