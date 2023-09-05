<?php

namespace App\Providers;

use App\Repositories\BaseRepository;
use App\Repositories\CartRepository;
use App\Repositories\IBaseRepository;
use App\Repositories\ICartRepository;
use App\Repositories\IProductRepository;
use App\Repositories\ProductRepository;
use Illuminate\Support\ServiceProvider;

class RepositoriesProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->app->bind(IBaseRepository::class, BaseRepository::class);
        $this->app->bind(IProductRepository::class, ProductRepository::class);
        $this->app->bind(ICartRepository::class, CartRepository::class);
    }
}
