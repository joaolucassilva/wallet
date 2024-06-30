<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

use App\Domain\Entities\UserEntity;

interface UserRepositoryInterface
{
    public function findById(int $id): UserEntity;
}
