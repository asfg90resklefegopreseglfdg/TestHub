<?php

namespace App\Providers;

use App\Repositories\Eloquent\QuestionRepository;
use App\Repositories\Eloquent\StatisticsRepository;
use App\Repositories\Eloquent\TestRepository;
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
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    public $singletons = [
        TestRepository::class,
        UserRepository::class,
        StatisticsRepository::class,
        QuestionRepository::class,

    ];
}
