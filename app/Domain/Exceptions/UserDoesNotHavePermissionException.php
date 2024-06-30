<?php

declare(strict_types=1);

namespace App\Domain\Exceptions;

use Exception;

class UserDoesNotHavePermissionException extends Exception
{
    protected $message = 'User does not have permission.';
}
