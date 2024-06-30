<?php

declare(strict_types=1);

namespace App\Domain\Enums;

enum TransferStatusEnum: string
{
    case PENDING = 'pending';
    case COMPLETED = 'completed';
    case FAILED = 'failed';
}
