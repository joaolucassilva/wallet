<?php

declare(strict_types=1);

namespace App\Domain\Database\Repositories;

use App\Domain\Entities\TransferEntity;

interface TransferRepositoryInterface
{
    public function create(TransferEntity $entity): void;
}
