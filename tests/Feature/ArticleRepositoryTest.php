<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Repositories\ArticleRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_articles(): void
    {
        Article::factory()->count(5)->create();

        $elasticsearchService = $this->createMock(originalClassName: \App\Services\ElasticsearchService::class);
        $repository = new ArticleRepository(elasticsearchService: $elasticsearchService);

        $articles = $repository->getArticles();

        $this->assertCount(expectedCount: 5, haystack: $articles);
    }

    public function test_get_articles_with_filters(): void
    {
        Article::factory()->create(['source' => 'NewsAPI']);
        Article::factory()->create(['source' => 'The Guardian']);

        $elasticsearchService = $this->createMock(originalClassName: \App\Services\ElasticsearchService::class);
        $repository = new ArticleRepository(elasticsearchService: $elasticsearchService);

        $articles = $repository->getArticles(filters: ['source' => 'NewsAPI']);

        $this->assertCount(expectedCount: 1, haystack: $articles);
        $this->assertEquals(expected: 'NewsAPI', actual: $articles->first()->source);
    }
}
