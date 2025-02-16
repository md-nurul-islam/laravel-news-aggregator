<?php

namespace App\Services;

use App\Models\Article;
use App\Services\Contracts\NewsApiServiceInterface;

class NewsApiService extends BaseNewsApiService implements NewsApiServiceInterface
{
    public function fetchNewsFromAPI(): void
    {
        $response = $this->client->get('https://newsapi.org/v2/top-headlines', [
            'query' => [
                'apiKey' => env('NEWSAPI_KEY'),
                'sources' => 'bbc-news,cnn',
                'pageSize' => 100,
            ],
        ]);

        $articles = json_decode(json: $response->getBody(), associative: true)['articles'];

        foreach ($articles as $article) {
            try {
                Article::updateOrCreate(
                    ['url' => $article['url']],
                    [
                        'title' => $article['title'],
                        'description' => $article['content'] ?? null,
                        'source' => 'NewsAPI',
                        'category' => $article['category'] ?? null,
                        'author' => $article['author'],
                        'url' => $article['url'],
                        'image_url' => $article['urlToImage'],
                        'published_at' => $article['publishedAt'],
                    ]
                );
            } catch (\Exception $e) {
                dd($e->getMessage());
            }

        }
    }
}
