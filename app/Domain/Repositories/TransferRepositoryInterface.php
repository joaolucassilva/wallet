<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

use App\Domain\Entities\TransferEntity;

interface TransferRepositoryInterface
{
    public function create(TransferEntity $entity): void;
}
