<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\TransactionRepositoryInterface;
use App\Repositories\TransactionRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(TransactionRepositoryInterface::class, TransactionRepository::class);
        $this->app->bind(\App\Interfaces\ProductRepositoryInterface::class, \App\Repositories\ProductRepository::class);
    }

    public function boot(): void
    {
        //
    }
}
