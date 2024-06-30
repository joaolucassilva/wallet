<?php

declare(strict_types=1);

namespace App\Domain\Entities;

use App\Domain\ValueObjects\Money;
use Exception;

class WalletEntity
{
    public function __construct(
        private readonly int $id,
        private readonly UserEntity $userEntity,
        private Money $balance,
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
     * @throws Exception
     */
    public function debit(Money $amount): self
    {
        if ($this->balance->getAmountInCents() === 0 ||
            $amount->getAmountInCents() > $this->balance->getAmountInCents()
        ) {
            throw new Exception('Insufficient balance');
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
