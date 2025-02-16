<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserPreference;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('secret'),
            'remember_token' => Str::random(10),
        ]);

        UserPreference::factory()->create(
            [
                'user_id' => 1,
                'source' => 'The Guardian',
                'category' => 'article',
            ],

        );

        UserPreference::factory()->create(
            [
                'user_id' => 1,
                'source' => 'The New York Times',
                'category' => 'News',
            ],
        );
    }
}
