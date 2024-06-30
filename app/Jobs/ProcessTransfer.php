<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Applications\Tranfers\InputDTO;
use App\Applications\Tranfers\TransferApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessTransfer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private readonly InputDTO $input,
    ) {
    }

    public function handle(TransferApplication $application): void
    {
        $application->execute($this->input);
    }
}
