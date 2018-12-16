<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            \App\Domain\Model\EventRepositoryInterface::class, 
            \App\Persistence\FileCacheEventRepository::class
        );

        $this->app->bind(
            \App\Domain\Model\ParticipantRepositoryInterface::class, 
            \App\Persistence\FileCacheParticipantRepository::class
        );
    }
}
