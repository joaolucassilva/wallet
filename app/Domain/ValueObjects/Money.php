<?php

declare(strict_types=1);

namespace App\Domain\ValueObjects;

class Money
{
    public function __construct(
        private readonly int $amount
    ) {
    }

    public function getAmountInDecimals(): float
    {
        return $this->amount;
    }

    public function getAmountInCents(): int
    {
        return $this->amount;
    }

    public static function setAmountDecimal(float $amount): self
    {
        return new self(
            amount: (int)($amount * 100)
        );
    }
}
