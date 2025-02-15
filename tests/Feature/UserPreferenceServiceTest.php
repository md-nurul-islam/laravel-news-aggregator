<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\UserPreferenceService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserPreferenceServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_preferences(): void
    {
        $user = User::factory()->create();
        $service = new UserPreferenceService();

        $preferences = $service->getPreferences(userId: $user->id);

        $this->assertNull(actual: $preferences);
    }

    public function test_update_preferences(): void
    {
        $user = User::factory()->create();
        $service = new UserPreferenceService();

        $preferences = $service->updatePreferences(userId: $user->id, data: [
            'source' => 'NewsAPI',
            'category' => 'Technology',
        ]);

        $this->assertDatabaseHas(table: 'user_preferences', data: [
            'user_id' => $user->id,
            'source' => 'NewsAPI',
            'category' => 'Technology',
        ]);
    }
}
