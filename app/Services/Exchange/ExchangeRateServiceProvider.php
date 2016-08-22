<?php

namespace App\Services\Exchange;

use App\Services\Exchange\Repositories\ExchangeRepository;
use Illuminate\Support\ServiceProvider;


class ExchangeRateServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind('App\Services\Exchange\ExchangeRate', function ($app) {
            return new ExchangeRate(new ExchangeRepository($app['events']));
        });
    }

    public function provides()
    {
        return [
            ExchangeRate::class,
            ExchangeRepository::class
        ];
    }

}