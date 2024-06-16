<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            'App\Repositories\User\UserRepositoryInterface',
            'App\Repositories\User\UserRepository'
        );

        $this->app->bind(
            'App\Repositories\Wallet\WalletRepositoryInterface',
            'App\Repositories\Wallet\WalletRepository'
        );

        $this->app->bind(
            'App\Repositories\Transaction\TransactionRepositoryInterface',
            'App\Repositories\Transaction\TransactionRepository'
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
