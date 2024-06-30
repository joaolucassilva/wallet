<?php

declare(strict_types=1);

namespace App\Domain\Exceptions;

use Exception;

class PaymentAuthorizeException extends Exception
{
    protected $message = 'Payment AuthorizeException';
}
