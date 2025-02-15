<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserPreference;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserPreferenceTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_preference_creation(): void
    {
        $user = User::factory()->create();
        UserPreference::factory()->create([
            'user_id' => $user->id,
            'source' => 'NewsAPI',
        ]);

        $this->assertDatabaseHas(table: 'user_preferences', data: [
            'user_id' => $user->id,
            'source' => 'NewsAPI',
        ]);
    }
}
