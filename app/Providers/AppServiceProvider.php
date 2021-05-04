<?php

namespace App\Providers;

// use App\Container\SettingLaunch;
use App\Repositories\Room\IRoomRepositoryInterface;
use App\Repositories\Room\RoomRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        // $this->app->bind(SettingLaunch::class, function ($app){
        //     return new SettingLaunch("longle");
        // });

        $this->app->singleton(
            IRoomRepositoryInterface::class,
            RoomRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
