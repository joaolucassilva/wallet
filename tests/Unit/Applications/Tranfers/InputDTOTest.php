<?php

declare(strict_types=1);

namespace Tests\Unit\Applications\Tranfers;

use App\Applications\Tranfers\InputDTO;
use App\Domain\ValueObjects\Money;
use PHPUnit\Framework\TestCase;

class InputDTOTest extends TestCase
{
    public function test_input_dto(): void
    {
        $dto = new InputDTO(
            payer: 4,
            payee: 15,
            amount: new Money(10000),
        );

        $this->assertSame(4, $dto->payer);
        $this->assertSame(15, $dto->payee);
        $this->assertInstanceOf(Money::class, $dto->amount);
    }
}
