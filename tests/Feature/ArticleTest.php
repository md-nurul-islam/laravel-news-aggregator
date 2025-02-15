<?php

namespace Tests\Feature;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleTest extends TestCase
{
    use RefreshDatabase;

    public function test_article_creation(): void
    {
        $article = Article::factory()->create([
            'title' => 'Test Article',
            'source' => 'Test Source',
        ]);

        $this->assertDatabaseHas(table: 'articles', data: [
            'title' => 'Test Article',
            'source' => 'Test Source',
        ]);
    }

    public function test_article_filtering_by_source(): void
    {
        Article::factory()->create(['source' => 'NewsAPI']);
        Article::factory()->create(['source' => 'The Guardian']);

        $response = $this->getJson(uri: '/api/articles?source=NewsAPI');

        $response->assertStatus(status: 200)
                 ->assertJsonFragment(data: ['source' => 'NewsAPI'])
                 ->assertJsonMissing(data: ['source' => 'The Guardian']);
    }
}
