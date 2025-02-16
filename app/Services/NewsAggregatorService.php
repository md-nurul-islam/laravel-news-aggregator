<?php

namespace App\Services;

use App\Services\Contracts\NewsApiServiceInterface;

class NewsAggregatorService
{
    private NewsApiServiceInterface $newsService;

    public function __construct(NewsApiServiceInterface $newsService)
    {
        $this->newsService = $newsService;
    }

    public function fetchAndStoreNews(): void
    {
        $this->newsService->fetchNewsFromAPI();
    }
}
