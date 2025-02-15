<?php

namespace App\Observers;

use App\Models\Article;
use App\Services\ElasticsearchService;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;

class ArticleObserver implements ShouldHandleEventsAfterCommit
{
    private ElasticsearchService $elasticSearchService;

    public function __construct()
    {
        $this->elasticSearchService = new ElasticsearchService();
    }

    public function created(Article $article): void
    {
        $this->elasticSearchService->indexArticle(article: $article);
    }

    public function updated(Article $article): void
    {
        $this->elasticSearchService->updateArticle(article: $article);
    }

    public function deleted(Article $article): void
    {
        $this->elasticSearchService->deleteArticle(article: $article);
    }

    public function restored(Article $article): void
    {
        $this->created(article: $article);
    }

    public function forceDeleted(Article $article): void
    {
        $this->deleted(article: $article);
    }
}
