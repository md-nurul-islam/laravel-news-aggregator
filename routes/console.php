<?php

use App\Services\GuardianApiService;
use App\Services\NewsAggregatorService;
use App\Services\NewsApiService;
use App\Services\NewYortTimesApiService;
use Illuminate\Support\Facades\Schedule;

Schedule::name('fetch:nyt')->call(function (): void {
    $newsService = new NewsAggregatorService(
        newsService: new NewYortTimesApiService()
    );
    $newsService->fetchAndStoreNews();
})
    ->cron('0 * * * *')
    ->withoutOverlapping()
    ->onOneServer()
    ->sendOutputTo('storage/logs/news-fetcher.log')
;

Schedule::name('fetch:newsApi')->call(function (): void {
    $newsService = new NewsAggregatorService(
        newsService: new NewsApiService()
    );
    $newsService->fetchAndStoreNews();
})
    ->cron('15 * * * *')
    ->withoutOverlapping()
    ->onOneServer()
    ->sendOutputTo('storage/logs/news-fetcher.log')
;

Schedule::name('fetch:guardian')->call(function (): void {
    $newsService = new NewsAggregatorService(
        newsService: new GuardianApiService()
    );
    $newsService->fetchAndStoreNews();
})
    ->cron('30 * * * *')
    ->withoutOverlapping()
    ->onOneServer()
    ->sendOutputTo('storage/logs/news-fetcher.log')
;
