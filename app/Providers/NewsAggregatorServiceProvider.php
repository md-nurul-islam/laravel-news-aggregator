<?php

namespace App\Providers;

use App\Services\NewsAggregatorService;
use Illuminate\Support\ServiceProvider;

class NewsAggregatorServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(abstract: NewsAggregatorService::class, concrete: function ($app): NewsAggregatorService {
            return new NewsAggregatorService();
        });
    }

    public function boot(): void
    {
        //
    }
}
