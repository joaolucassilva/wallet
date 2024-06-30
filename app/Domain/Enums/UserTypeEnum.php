<?php

declare(strict_types=1);

namespace App\Domain\Enums;

enum UserTypeEnum: string
{
    case LEGAL_PERSON = 'legal_person';
    case NATURAL_PERSON = 'natural_person';
}
