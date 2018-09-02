<?php

namespace App\Providers;

use App\Repositories\Contracts\StatisticsRepositoryContract;
use App\Repositories\Contracts\TestRepositoryContract;
use App\Repositories\Contracts\UserRepositoryContract;
use App\Repositories\Eloquent\StatisticsRepository;
use App\Repositories\Eloquent\UserRepository;
use Illuminate\Support\ServiceProvider;


class RepositoryProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

    }

    public $bindings = [
        TestRepositoryContract::class => TestRepositoryContract::class,
        UserRepositoryContract::class => UserRepository::class,
        StatisticsRepositoryContract::class => StatisticsRepository::class,
    ];
}
