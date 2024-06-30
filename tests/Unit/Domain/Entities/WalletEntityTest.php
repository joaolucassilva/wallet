<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Entities;

use App\Domain\Entities\UserEntity;
use App\Domain\Entities\WalletEntity;
use App\Domain\Enums\UserTypeEnum;
use App\Domain\Exceptions\InsufficientBalanceException;
use App\Domain\Exceptions\UserDoesNotHavePermissionException;
use App\Domain\ValueObjects\Money;
use App\Domain\ValueObjects\UUID;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class WalletEntityTest extends TestCase
{
    /**
     * @throws InsufficientBalanceException
     * @throws UserDoesNotHavePermissionException
     */
    public function test_create_debit_with_success(): void
    {
        $userEntity = new UserEntity(
            id: 1,
            uuid: UUID::generate(),
            name: 'test',
            email: 'test@test.com',
            phone: '0123456789',
            password: '12345',
            type: UserTypeEnum::NATURAL_PERSON,
            document: '12312312333',
            createdAt: new DateTimeImmutable(),
            updatedAt: new DateTimeImmutable(),
        );

        $wallet = new WalletEntity(
            id: 1,
            uuid: UUID::generate(),
            userEntity: $userEntity,
            balance: new Money(10000),
            createdAt: new DateTimeImmutable(),
            updatedAt: new DateTimeImmutable()
        );

        $oldBalance = $wallet->getBalance();

        $wallet->debit(new Money(1000));

        $this->assertSame(1, $wallet->getId());
        $this->assertNotEquals($oldBalance, $wallet->getBalance());
        $this->assertSame(9000, $wallet->getBalance()->getAmountInCents());
    }

    /**
     * @throws InsufficientBalanceException
     */
    public function test_create_debit_when_user_cannot_initiate_transfer(): void
    {
        $this->expectException(UserDoesNotHavePermissionException::class);

        $userEntity = new UserEntity(
            id: 1,
            uuid: UUID::generate(),
            name: 'test',
            email: 'test@test.com',
            phone: '0123456789',
            password: '12345',
            type: UserTypeEnum::LEGAL_PERSON,
            document: '12312312333',
            createdAt: new DateTimeImmutable(),
            updatedAt: new DateTimeImmutable(),
        );

        $wallet = new WalletEntity(
            id: 1,
            uuid: UUID::generate(),
            userEntity: $userEntity,
            balance: new Money(10000),
            createdAt: new DateTimeImmutable(),
            updatedAt: new DateTimeImmutable()
        );

        $wallet->debit(new Money(1000));
    }

    /**
     * @throws UserDoesNotHavePermissionException
     */
    public function test_create_debit_when_balance_equals_zero_should_generate_exception(): void
    {
        $this->expectException(InsufficientBalanceException::class);

        $userEntity = new UserEntity(
            id: 1,
            uuid: UUID::generate(),
            name: 'test',
            email: 'test@test.com',
            phone: '0123456789',
            password: '12345',
            type: UserTypeEnum::NATURAL_PERSON,
            document: '12312312333',
            createdAt: new DateTimeImmutable(),
            updatedAt: new DateTimeImmutable(),
        );

        $wallet = new WalletEntity(
            id: 1,
            uuid: UUID::generate(),
            userEntity: $userEntity,
            balance: new Money(0),
            createdAt: new DateTimeImmutable(),
            updatedAt: new DateTimeImmutable()
        );

        $wallet->debit(new Money(1000));
    }

    /**
     * @throws UserDoesNotHavePermissionException
     */
    public function test_create_debit_when_insufficiente_balance_should_generate_exception(): void
    {
        $this->expectException(InsufficientBalanceException::class);

        $userEntity = new UserEntity(
            id: 1,
            uuid: UUID::generate(),
            name: 'test',
            email: 'test@test.com',
            phone: '0123456789',
            password: '12345',
            type: UserTypeEnum::NATURAL_PERSON,
            document: '12312312333',
            createdAt: new DateTimeImmutable(),
            updatedAt: new DateTimeImmutable(),
        );

        $wallet = new WalletEntity(
            id: 1,
            uuid: UUID::generate(),
            userEntity: $userEntity,
            balance: new Money(100),
            createdAt: new DateTimeImmutable(),
            updatedAt: new DateTimeImmutable()
        );

        $wallet->debit(new Money(1000));
    }

    public function test_create_credit(): void
    {
        $userEntity = new UserEntity(
            id: 1,
            uuid: UUID::generate(),
            name: 'test',
            email: 'test@test.com',
            phone: '0123456789',
            password: '12345',
            type: UserTypeEnum::NATURAL_PERSON,
            document: '12312312333',
            createdAt: new DateTimeImmutable(),
            updatedAt: new DateTimeImmutable(),
        );

        $wallet = new WalletEntity(
            id: 1,
            uuid: UUID::generate(),
            userEntity: $userEntity,
            balance: new Money(10000),
            createdAt: new DateTimeImmutable(),
            updatedAt: new DateTimeImmutable()
        );

        $oldBalance = $wallet->getBalance();

        $wallet->credit(new Money(1000));

        $this->assertSame(1, $wallet->getId());
        $this->assertNotEquals($oldBalance, $wallet->getBalance());
        $this->assertSame(11000, $wallet->getBalance()->getAmountInCents());
    }
}
