<?php

declare(strict_types=1);

namespace App\Domain\Entities;

use App\Domain\Enums\UserTypeEnum;
use App\Domain\ValueObjects\UUID;
use Exception;

class UserEntity
{
    public function __construct(
        private int $id,
        private UUID $uuid,
        private string $name,
        private string $email,
        private string $password,
        private UserTypeEnum $type,
        private ?WalletEntity $wallet = null,
    ) {
    }

    public function getType(): UserTypeEnum
    {
        return $this->type;
    }

    public function getWallet(): ?WalletEntity
    {
        return $this->wallet;
    }

    /**
     * @throws Exception
     */
    public function canInitiateTransfer(): bool
    {
        return $this->type === UserTypeEnum::NATURAL_PERSON;
    }
}
