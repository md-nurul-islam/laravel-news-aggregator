<?php

namespace App\Repositories;

use App\Models\Article;
use App\Services\ElasticsearchService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ArticleRepository
{
    protected ElasticsearchService $elasticsearchService;

    public function __construct(ElasticsearchService $elasticsearchService)
    {
        $this->elasticsearchService = $elasticsearchService;
    }

    public function getArticles($filters = []): mixed
    {
        if (isset($filters['q'])) {
            return $this->elasticsearchService->searchArticles(query: $filters['q']);
        }

        $cacheKey = 'articles_' . md5(string: serialize(value: $filters));

        return Cache::remember(key: $cacheKey, ttl: 60, callback: function () use ($filters): LengthAwarePaginator {
            $query = Article::query();

            if (isset($filters['source'])) {
                $query->where(column: 'source', operator: $filters['source']);
            }

            if (isset($filters['category'])) {
                $query->where(column: 'category', operator: $filters['category']);
            }

            if (isset($filters['date'])) {
                $query->whereDate(column: 'published_at', operator: $filters['date']);
            }

            if (isset($filters['q'])) {
                $query->where(column: 'title', operator: 'like', value: '%' . $filters['q'] . '%')
                    ->orWhere(column: 'description', operator: 'like', value: '%' . $filters['q'] . '%');
            }

            return $query->paginate(perPage: 20);
        });
    }
}
