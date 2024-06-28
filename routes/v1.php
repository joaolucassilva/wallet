<?php

declare(strict_types=1);

use App\Http\Controllers\HealthCheckController;
use Illuminate\Support\Facades\Route;

Route::get('/health', HealthCheckController::class)
    ->name('health');
