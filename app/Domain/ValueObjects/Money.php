<?php

declare(strict_types=1);

namespace App\Domain\ValueObjects;

class Money
{
    public function __construct(
        private int $amount
    ) {
    }

    public function getAmountInDecimals(): float
    {
        return $this->amount;
    }

    public function getAmountInCents(): int
    {
        return (int)round($this->amount * 100);
    }

    public function setAmount(float $amount): void
    {
        $this->amount = (int)round($amount * 100);
    }
}
