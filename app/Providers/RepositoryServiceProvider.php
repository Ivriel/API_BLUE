<?php

namespace App\Providers;

use App\Interfaces\BuyerRepositoryInterface;
use App\Interfaces\ProductCategoryRepositoryInterface;
use App\Interfaces\StoreBalanceHistoryRepositoryInterface;
use App\Interfaces\StoreBalanceRepositoryInterface;
use App\Interfaces\StoreRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Interfaces\WithdrawalRepositoryInterface;
use App\Repositories\BuyerRepository;
use App\Repositories\ProductCategoryRepository;
use App\Repositories\StoreBalanceHistoryRepository;
use App\Repositories\StoreBalanceRepository;
use App\Repositories\StoreRepository;
use App\Repositories\UserRepository;
use App\Repositories\WithdrawalRepository;
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
        $this->app->bind(WithdrawalRepositoryInterface::class, WithdrawalRepository::class);
        $this->app->bind(BuyerRepositoryInterface::class, BuyerRepository::class);
        $this->app->bind(ProductCategoryRepositoryInterface::class, ProductCategoryRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
