<?php

declare(strict_types=1);

namespace Tests\Unit\ValueObjects;

use App\Domain\ValueObjects\Money;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

class MoneyTest extends TestCase
{
    #[TestWith([12345, 123.45])]
    #[TestWith([1220, 12.2])]
    #[TestWith([10000, 100.0])]
    #[TestWith([10200, 102.0])]
    #[TestWith([200, 2.0])]
    #[TestWith([50, 0.50])]
    #[TestWith([50, 0.5])]
    #[TestWith([150, 1.50])]
    public function test_get_money_in_cents(int $expect, float $input): void
    {
        $money = new Money($input);

        $this->assertSame($expect, $money->getAmountInCents());
    }

    #[TestWith([123.45, 123.45])]
    #[TestWith([12.2, 12.2])]
    #[TestWith([211.22, 211.22])]
    #[TestWith([1000, 1000])]
    public function test_get_money_in_decimals(float $expect, float $input): void
    {
        $money = new Money($input);

        $this->assertSame($expect, $money->getAmountInDecimals());
    }
}
