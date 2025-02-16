<?php

namespace App\Services;

use App\Models\Article;
use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;

class ElasticsearchService
{
    private const INDEX = 'articles';
    protected Client $client;

    public function __construct()
    {
        $this->client = ClientBuilder::create()
            ->setHosts([env('ELASTICSEARCH_HOST', 'http://localhost:9200')])
            ->build();
    }

    public function indexArticle(Article $article): void
    {
        $params = [
            'index' => self::INDEX,
            'id' => $article->id,
            'body' => $article->toArray(),
        ];

        $this->client->index($params);
    }

    public function updateArticle(Article $article): void
    {
        $this->indexArticle(article: $article);
    }

    public function deleteArticle(Article $article): void
    {
        $params = [
            'index' => self::INDEX,
            'id' => $article->id,
        ];

        $this->client->delete($params);
    }

    public function searchArticles(string $query, int $from = 0, int $perPage = 20): array
    {
        $params = [
            'index' => 'articles',
            'body' => [
                'from' => $from,
                'size' => $perPage,
                'query' => [
                    'multi_match' => [
                        'query' => $query,
                        'fields' => ['title', 'description'],
                    ],
                ],
            ],
        ];

        return $this->client->search($params);
    }
}
