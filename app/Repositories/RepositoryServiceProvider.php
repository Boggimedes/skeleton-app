<?php

namespace App\Repositories;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Bind the repository interfaces to their implementations
        $this->app->bind(
            'App\Repositories\Contracts\BaseRepositoryInterface',
            'App\Repositories\Eloquent\BaseRepository'
        );

        // Bind additional model repositories here
        // $this->app->bind(
        //     'App\Repositories\Contracts\[ModelName]RepositoryInterface',
        //     'App\Repositories\Eloquent\[ModelName]Repository'
        // );
    }

    public function boot()
    {
        // Bootstrap any repository services if needed
    }
}