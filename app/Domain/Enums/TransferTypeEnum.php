<?php

declare(strict_types=1);

namespace App\Domain\Enums;

enum TransferTypeEnum: string
{
    case INCOMING = 'incoming';
    case OUTGOING = 'outgoing';
}
