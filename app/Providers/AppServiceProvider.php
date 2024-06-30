<?php

declare(strict_types=1);

namespace App\Providers;

use App\Adapters\UnitOfWork;
use App\Domain\UnitOfWorkInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            abstract: UnitOfWorkInterface::class,
            concrete: UnitOfWork::class,
        );
    }

    public function boot(): void
    {
        Model::preventLazyLoading(!app()->isProduction());
        Model::shouldBeStrict(!app()->isProduction());
    }
}
