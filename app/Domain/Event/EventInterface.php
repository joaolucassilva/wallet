<?php

declare(strict_types=1);

namespace App\Domain\Event;

interface EventInterface
{
    public function send(): void;
}
