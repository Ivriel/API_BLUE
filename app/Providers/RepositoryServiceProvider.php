<?php

namespace App\Providers;

use App\Interfaces\StoreBalanceHistoryRepositoryInterface;
use App\Interfaces\StoreBalanceRepositoryInterface;
use App\Interfaces\StoreRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Repositories\StoreBalanceHistoryRepository;
use App\Repositories\StoreBalanceRepository;
use App\Repositories\StoreRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(StoreRepositoryInterface::class, StoreRepository::class);
        $this->app->bind(StoreBalanceRepositoryInterface::class, StoreBalanceRepository::class);
        $this->app->bind(StoreBalanceHistoryRepositoryInterface::class, StoreBalanceHistoryRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
