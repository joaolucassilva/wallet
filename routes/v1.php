<?php

declare(strict_types=1);

use App\Http\Controllers\HealthCheckController;
use App\Http\Controllers\TransferController;
use Illuminate\Support\Facades\Route;

Route::get('/health', HealthCheckController::class)
    ->name('health');

Route::post('/transfer', TransferController::class)
    ->name('transfer');
