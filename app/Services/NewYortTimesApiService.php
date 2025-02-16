<?php

namespace App\Services;

use App\Models\Article;
use App\Services\Contracts\NewsApiServiceInterface;

class NewYortTimesApiService extends BaseNewsApiService implements NewsApiServiceInterface
{
    public function fetchNewsFromAPI(): void
    {
        $response = $this->client->get('https://api.nytimes.com/svc/search/v2/articlesearch.json', [
            'query' => [
                'api-key' => env('NYT_API_KEY'),
                'facet_fields' => 'news_desk',
                'sort' => 'newest',
            ],
        ]);

        $articles = json_decode($response->getBody(), true)['response']['docs'];

        foreach ($articles as $article) {
            try {
                Article::updateOrCreate(
                    ['url' => $article['web_url']],
                    [
                        'title' => $article['headline']['main'],
                        'description' => $article['lead_paragraph'] ?? null,
                        'source' => 'The New York Times',
                        'category' => $article['type_of_material'] ?? null,
                        'author' => !empty($article['byline']['person']) ? $article['byline']['person'][0]['firstname'] . ' ' . $article['byline']['person'][0]['lastname'] : null,
                        'url' => $article['web_url'],
                        'image_url' => !empty($article['multimedia']) ? 'https://www.nytimes.com/' . $article['multimedia'][0]['url'] : null,
                        'published_at' => $article['pub_date'],
                    ]
                );
            } catch (\Exception $e) {
                dd($e->getMessage());
            }
        }
    }
}
