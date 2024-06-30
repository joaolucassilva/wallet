<?php

declare(strict_types=1);

use App\Http\Controllers\TransferController;
use Illuminate\Support\Facades\Route;

Route::post('/transfer', TransferController::class)
    ->name('transfer');
