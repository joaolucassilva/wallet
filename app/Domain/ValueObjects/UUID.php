<?php

declare(strict_types=1);

namespace App\Domain\ValueObjects;

use InvalidArgumentException;

class UUID
{
    public function __construct(
        private readonly string $uuid = '',
    ) {
        $this->validate($this->uuid);
    }

    public static function generate(): self
    {
        return new self(\Ramsey\Uuid\Uuid::uuid4()->toString());
    }

    private function validate(string $uuid): void
    {
        if (!\Ramsey\Uuid\Uuid::isValid($uuid)) {
            throw new InvalidArgumentException('Invalid UUID');
        }
    }

    public function __toString(): string
    {
        return $this->uuid;
    }
}
