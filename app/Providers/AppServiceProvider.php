<?php

declare(strict_types=1);

namespace App\Providers;

use App\Adapters\Database\Repositories\EloquentORM\TransferRepository;
use App\Adapters\Database\Repositories\EloquentORM\WalletRepository;
use App\Adapters\Database\UnitOfWork;
use App\Adapters\Gateways\NotificationGateway;
use App\Adapters\Gateways\PaymentAuthorizationGateway;
use App\Domain\Database\Repositories\TransferRepositoryInterface;
use App\Domain\Database\Repositories\WalletRepositoryInterface;
use App\Domain\Database\UnitOfWorkInterface;
use App\Domain\Gateways\NotificationGatewayInterface;
use App\Domain\Gateways\PaymentAuthorizationGatewayInterface;
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

        $this->app
            ->bind(
                abstract: NotificationGatewayInterface::class,
                concrete: NotificationGateway::class,
            );

        $this->app
            ->bind(
                abstract: PaymentAuthorizationGatewayInterface::class,
                concrete: PaymentAuthorizationGateway::class,
            );


        // repositories
        $this->app->bind(
            abstract: WalletRepositoryInterface::class,
            concrete: WalletRepository::class,
        );
        $this->app->bind(
            abstract: TransferRepositoryInterface::class,
            concrete: TransferRepository::class,
        );
    }

    public function boot(): void
    {
        Model::preventLazyLoading(!app()->isProduction());
        Model::shouldBeStrict(!app()->isProduction());
    }
}
