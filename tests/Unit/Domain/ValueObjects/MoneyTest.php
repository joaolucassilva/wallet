<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\ValueObjects;

use App\Domain\ValueObjects\Money;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

class MoneyTest extends TestCase
{
    #[TestWith([12345, 12345])]
    #[TestWith([1220, 1220])]
    public function test_get_money_in_cents(int $expect, int $input): void
    {
        $money = new Money($input);

        $this->assertSame($expect, $money->getAmountInCents());
    }

    #[TestWith([10000, 100.0])]
    #[TestWith([15000, 150.0])]
    public function test_set_amount_float_transform_in_cents(int $expect, float $input): void
    {
        $result = Money::setAmountDecimal($input);

        $this->assertSame($expect, $result->getAmountInCents());
    }
}
