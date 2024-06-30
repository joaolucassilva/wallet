<?php

declare(strict_types=1);

namespace App\Domain\Exceptions;

use Exception;

class InsufficientBalanceException extends Exception
{
    protected $message = 'Insufficient balance';
}
