<?php

namespace App\Services;

use App\Models\UserPreference;
use Illuminate\Support\Facades\Redis;

class UserPreferenceService
{
    public function getPreferences($userId): mixed
    {
        $cacheKey = "user_preferences:$userId";

        if (Redis::exists($cacheKey)) {
            return json_decode(json: Redis::get($cacheKey), associative: true);
        }

        $preferences = UserPreference::where(column: 'user_id', operator: $userId)->first();

        Redis::set($cacheKey, json_encode(value: $preferences));
        Redis::expire($cacheKey, 3600); // Cache for 1 hour

        return $preferences;
    }

    public function updatePreferences($userId, $data): UserPreference
    {
        $preferences = UserPreference::updateOrCreate(
            attributes: ['user_id' => $userId],
            values: $data
        );

        $cacheKey = "user_preferences:$userId";
        Redis::set($cacheKey, json_encode(value: $preferences));
        Redis::expire($cacheKey, 3600); // Cache for 1 hour

        return $preferences;
    }

    public function clearPreferences($userId): void
    {
        UserPreference::where(column: 'user_id', operator: $userId)->delete();

        $cacheKey = "user_preferences:$userId";
        Redis::del($cacheKey);
    }
}
