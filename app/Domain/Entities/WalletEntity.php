<?php

declare(strict_types=1);

namespace App\Domain\Entities;

use App\Domain\Exceptions\InsufficientBalanceException;
use App\Domain\Exceptions\UserDoesNotHavePermissionException;
use App\Domain\ValueObjects\Money;
use App\Domain\ValueObjects\UUID;
use DateTimeImmutable;

class WalletEntity
{
    public function __construct(
        private readonly int $id,
        private readonly UUID $uuid,
        private readonly UserEntity $userEntity,
        private Money $balance,
        private readonly DateTimeImmutable $createdAt,
        private readonly DateTimeImmutable $updatedAt,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUser(): UserEntity
    {
        return $this->userEntity;
    }

    public function getBalance(): Money
    {
        return $this->balance;
    }

    /**
     * @param Money $amount
     * @return $this
     * @throws InsufficientBalanceException
     * @throws UserDoesNotHavePermissionException
     */
    public function debit(Money $amount): self
    {
        if (!$this->getUser()->canInitiateTransfer()) {
            throw new UserDoesNotHavePermissionException();
        }

        if (
            $this->balance->getAmountInCents() === 0 ||
            $amount->getAmountInCents() > $this->balance->getAmountInCents()
        ) {
            throw new InsufficientBalanceException();
        }

        $this->balance = new Money($this->balance->getAmountInCents() - $amount->getAmountInCents());

        return $this;
    }

    public function credit(Money $amount): self
    {
        $this->balance = new Money($this->balance->getAmountInCents() + $amount->getAmountInCents());

        return $this;
    }
}
