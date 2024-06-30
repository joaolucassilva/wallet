<?php

declare(strict_types=1);

namespace App\Domain\Entities;

use App\Domain\Enums\UserTypeEnum;
use App\Domain\ValueObjects\UUID;
use DateTimeImmutable;

class UserEntity
{
    public function __construct(
        private readonly int $id,
        private readonly UUID $uuid,
        private readonly string $name,
        private readonly string $email,
        private readonly string $phone,
        private readonly string $password,
        private readonly UserTypeEnum $type,
        private readonly string $document,
        private readonly DateTimeImmutable $createdAt,
        private readonly DateTimeImmutable $updatedAt,
    ) {
    }

    public function getType(): UserTypeEnum
    {
        return $this->type;
    }

    public function canInitiateTransfer(): bool
    {
        return $this->type === UserTypeEnum::NATURAL_PERSON;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }
}
