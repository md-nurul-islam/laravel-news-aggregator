<?php

namespace App\Services;

use App\Models\Article;
use App\Services\Contracts\NewsApiServiceInterface;

class GuardianApiService extends BaseNewsApiService implements NewsApiServiceInterface
{
    public function fetchNewsFromAPI(): void
    {
        $response = $this->client->get('https://content.guardianapis.com/search', [
            'query' => [
                'api-key' => env('GUARDIAN_API_KEY'),
                'page-size' => 100,
                'order-by' => 'newest',
            ],
        ]);

        $articles = json_decode(json: $response->getBody(), associative: true)['response']['results'];

        foreach ($articles as $article) {
            try {
                Article::updateOrCreate(
                    ['url' => $article['webUrl']],
                    [
                        'title' => $article['webTitle'],
                        'description' => $article['description'] ?? null,
                        'source' => 'The Guardian',
                        'category' => $article['type'] ?? null,
                        'author' => $article['author'] ?? 'The Guardian',
                        'url' => $article['webUrl'],
                        'image_url' => $article['imageUrl'] ?? null,
                        'published_at' => $article['webPublicationDate'],
                    ]
                );
            } catch (\Exception $e) {
                dd($e->getMessage());
            }
        }
    }
}
