<?php

declare(strict_types=1);

namespace App\Adapters\Database\Repositories\EloquentORM;

use App\Domain\Database\Repositories\WalletRepositoryInterface;
use App\Domain\Entities\UserEntity;
use App\Domain\Entities\WalletEntity;
use App\Domain\Enums\UserTypeEnum;
use App\Domain\Exceptions\WalletNotFoundException;
use App\Domain\ValueObjects\Money;
use App\Domain\ValueObjects\UUID;
use App\Models\Wallet;
use DateTimeImmutable;
use Exception;

class WalletRepository implements WalletRepositoryInterface
{
    public function __construct(
        private readonly Wallet $wallet,
    ) {
    }

    public function updateBalance(WalletEntity $walletEntity): void
    {

        $this->wallet
            ->newQuery()
            ->where('id', $walletEntity->getId())
            ->update([
                'balance' => $walletEntity->getBalance()->getAmountInCents(),
            ]);
    }

    /**
     * @throws WalletNotFoundException
     * @throws Exception
     */
    public function findByUserId(int $userId): WalletEntity
    {
        $walletDB = $this->wallet
            ->newQuery()
            ->with('user')
            ->where('user_id', $userId)
            ->first();

        if (is_null($walletDB)) {
            throw new WalletNotFoundException();
        }

        $user = $walletDB->user;

        return new WalletEntity(
            id: $walletDB->id,
            uuid: new UUID($walletDB->uuid),
            userEntity: new UserEntity(
                id: $user->id,
                uuid: new UUID($user->uuid),
                name: $user->name,
                email: $user->email,
                phone: $user->phone,
                password: $user->password,
                type: UserTypeEnum::from($user->type),
                document: $user->document,
                createdAt: new DateTimeImmutable($walletDB->created_at->format('Y-m-d H:i:s')),
                updatedAt: new DateTimeImmutable($walletDB->updated_at->format('Y-m-d H:i:s')),
            ),
            balance: new Money($walletDB->balance),
            createdAt: new DateTimeImmutable($walletDB->created_at->format('Y-m-d H:i:s')),
            updatedAt: new DateTimeImmutable($walletDB->updated_at->format('Y-m-d H:i:s')),
        );
    }
}
