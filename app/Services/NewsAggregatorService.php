<?php

namespace App\Services;

use GuzzleHttp\Client;
use App\Models\Article;

class NewsAggregatorService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function fetchFromNewsAPI()
    {
        $response = $this->client->get('https://newsapi.org/v2/top-headlines', [
            'query' => [
                'apiKey' => env('NEWSAPI_KEY'),
                'sources' => 'bbc-news,cnn',
                'pageSize' => 100,
            ],
        ]);

        $articles = json_decode($response->getBody(), true)['articles'];

        foreach ($articles as $article) {
            Article::updateOrCreate(
                ['url' => $article['url']],
                [
                    'title' => $article['title'],
                    'description' => $article['description'],
                    'source' => 'NewsAPI',
                    'category' => $article['category'] ?? null,
                    'author' => $article['author'],
                    'url' => $article['url'],
                    'image_url' => $article['urlToImage'],
                    'published_at' => $article['publishedAt'],
                ]
            );
        }
    }

    // Add similar methods for The Guardian and New York Times APIs
}
