<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleControllerTest extends TestCase
{
    use RefreshDatabase;
    public function test_get_articles_without_filters(): void
    {
        Article::factory()->count(5)->create();

        $response = $this->getJson(uri: '/api/articles');

        $response->assertStatus(status: 200)
            ->assertJsonCount(count: 5, key: 'data');
    }

    public function test_get_articles_with_filters()
    {
        Article::factory()->create(['source' => 'NewsAPI']);
        Article::factory()->create(['source' => 'The Guardian']);

        $response = $this->getJson(uri: '/api/articles?source=NewsAPI');

        $response->assertStatus(status: 200)
            ->assertJsonFragment(data: ['source' => 'NewsAPI'])
            ->assertJsonMissing(data: ['source' => 'The Guardian']);
    }

    public function test_get_articles_with_user_preferences()
    {
        $user = User::factory()->create();
        $this->actingAs(user: $user, guard: 'api');

        $preferences = ['source' => 'NewsAPI', 'category' => 'Technology'];
        $this->postJson(uri: '/api/preferences', data: $preferences);

        Article::factory()->create(['source' => 'NewsAPI', 'category' => 'Technology']);
        Article::factory()->create(['source' => 'The Guardian', 'category' => 'Politics']);

        $response = $this->getJson('/api/articles');

        $response->assertStatus(status: 200)
            ->assertJsonFragment(data: ['source' => 'NewsAPI', 'category' => 'Technology'])
            ->assertJsonMissing(data: ['source' => 'The Guardian', 'category' => 'Politics']);
    }
}
