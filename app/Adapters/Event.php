<?php

declare(strict_types=1);

namespace App\Adapters;

class Event
{
    public function send(): void
    {
        event();
    }
}
