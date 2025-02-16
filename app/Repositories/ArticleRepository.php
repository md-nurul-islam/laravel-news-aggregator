<?php

namespace App\Repositories;

use App\Models\Article;
use App\Services\ElasticsearchService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class ArticleRepository
{
    private const PAGE_SIZE = 20;
    protected ElasticsearchService $elasticsearchService;

    public function __construct(ElasticsearchService $elasticsearchService)
    {
        $this->elasticsearchService = $elasticsearchService;
    }

    public function getArticles($filters = []): mixed
    {
        if (isset($filters['q'])) {
            $currentPage = $filters['page'] ?? 1;
            $from = ($currentPage - 1) * self::PAGE_SIZE;
            
            $articles = $this->elasticsearchService->searchArticles(
                query: $filters['q'],
                from: $from,
                perPage: self::PAGE_SIZE
            );

            return $this->prepareArticleDataFromElasticsearch(articles: $articles['hits'], currentPage: $currentPage);
        }

        $cacheKey = 'articles_' . md5(string: serialize(value: $filters));

        return Cache::remember(key: $cacheKey, ttl: 60, callback: function () use ($filters): LengthAwarePaginator {
            $query = Article::query();

            if (!empty($filters['source'])) {
                $query->whereIn(column: 'source', values: $filters['source']);
            }

            if (!empty($filters['category'])) {
                $query->whereIn(column: 'category', values: $filters['category']);
            }

            if (isset($filters['date'])) {
                $query->whereDate(column: 'published_at', operator: $filters['date']);
            }

            if (isset($filters['q'])) {
                $query->where(column: 'title', operator: 'like', value: '%' . $filters['q'] . '%')
                    ->orWhere(column: 'description', operator: 'like', value: '%' . $filters['q'] . '%');
            }

            return $query->paginate(perPage: self::PAGE_SIZE);
        });
    }

    public function find(int $id): ?Article
    {
        return Article::find($id);
    }

    private function prepareArticleDataFromElasticsearch(array $articles, int $currentPage = 1)
    {
        $data = [];
        foreach ($articles['hits'] as $article) {
            $data[] = $article['_source'];
        }

        $pagination = new LengthAwarePaginator(
            items: $data,
            total: $articles['total']['value'],
            perPage: self::PAGE_SIZE,
            currentPage: $currentPage,
            options: ['path' => url()->current()]
        );

        return $pagination;
    }
}
