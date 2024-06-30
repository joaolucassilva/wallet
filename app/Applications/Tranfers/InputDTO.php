<?php

declare(strict_types=1);

namespace App\Applications\Tranfers;

use App\Domain\ValueObjects\Money;
use Spatie\LaravelData\Data;

class InputDTO extends Data
{
    public function __construct(
        public readonly int $payer,
        public readonly int $payee,
        public readonly Money $amount,
    ) {
    }
}
