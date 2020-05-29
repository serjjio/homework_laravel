<?php

namespace App\Providers;

use App\Building;
use App\City;
use App\Services\Auth\AuthJwtToken;
use App\Services\Auth\AuthTokenInterface;
use App\Services\CheckMaxIdToModels;
use App\Services\GetFullNameStreet;
use App\Services\Role\CheckUserRole;
use App\Street;
use App\StreetProviderInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public $singletons = [
        StreetProviderInterface::class => Street::class,
        City::class => City::class,
        Building::class => Building::class,
        AuthTokenInterface::class => AuthJwtToken::class,
        CheckUserRole::class => CheckUserRole::class,
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(GetFullNameStreet::class, function ($app) {
            return new GetFullNameStreet($app->make(StreetProviderInterface::class));
        });

        $this->app->tag([StreetProviderInterface::class, City::class, Building::class], 'models');

        $this->app->bind(CheckMaxIdToModels::class, function ($app) {
            return new CheckMaxIdToModels($app->tagged('models'));
        });
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
