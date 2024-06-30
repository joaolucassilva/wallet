<?php

declare(strict_types=1);

namespace App\Adapters\Database;

use App\Domain\Database\UnitOfWorkInterface;
use Illuminate\Support\Facades\DB;

class UnitOfWork implements UnitOfWorkInterface
{
    public function beginTransaction(): void
    {
        DB::beginTransaction();
    }

    public function commit(): void
    {
        DB::commit();
    }

    public function rollback(): void
    {
        DB::rollBack();
    }
}
