<?php

declare(strict_types=1);

namespace App\Adapters\Database\Repositories\EloquentORM;

use App\Domain\Database\Repositories\UserRepositoryInterface;
use App\Domain\Entities\UserEntity;
use App\Domain\Entities\WalletEntity;
use App\Domain\Enums\UserTypeEnum;
use App\Domain\Exceptions\UserNotFoundException;
use App\Domain\ValueObjects\UUID;
use App\Models\User;
use DateTimeImmutable;

class UserRepository implements UserRepositoryInterface
{
    public function __construct(
        private readonly User $user,
    ) {
    }

    /**
     * @throws UserNotFoundException
     * @throws \Exception
     */
    public function findById(int $id): UserEntity
    {
        $userDB = $this->user
            ->newQuery()
            ->with('wallet')
            ->find($id);

        if (is_null($userDB)) {
            throw new UserNotFoundException();
        }

        $walletDB = $userDB->wallet;

        return new UserEntity(
            id: $userDB->id,
            uuid: new UUID($userDB->uuid),
            name: $userDB->name,
            email: $userDB->email,
            phone: $userDB->phone,
            password: $userDB->password,
            type: UserTypeEnum::from($userDB->type),
            document: $userDB->document,
            wallet: new WalletEntity(
                id: $walletDB->id,
                uuid: $walletDB->uuid,
                balance: $walletDB->balance,
                createdAt: $walletDB->created_at,
                updatedAt: $walletDB->updated_at,
            ),
            createdAt: new DateTimeImmutable($userDB->created_at),
            updatedAt: new DateTimeImmutable($userDB->created_at),
        );
    }
}
