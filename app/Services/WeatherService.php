<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class WeatherService
{
    public function current(): ?array
    {
        $cacheKey = 'spamast_weather_current';
        $lastKnownKey = 'spamast_weather_last_known';

        try {
            return Cache::remember($cacheKey, now()->addMinutes(30), function () use ($lastKnownKey) {
                $response = Http::timeout(3)
                    ->get(config('services.weather.url'), [
                        'latitude' => config('services.weather.latitude'),
                        'longitude' => config('services.weather.longitude'),
                        'current' => 'temperature_2m,relative_humidity_2m,apparent_temperature,is_day,precipitation,weather_code,wind_speed_10m',
                        'timezone' => 'Asia/Manila',
                    ]);

                if (!$response->successful()) {
                    throw new \RuntimeException('Weather API request failed.');
                }

                $weather = $response->json('current');

                if (!is_array($weather)) {
                    throw new \RuntimeException('Invalid weather API response.');
                }

                $weather['condition'] = $this->conditionLabel(
                    $weather['weather_code'] ?? null
                );

                Cache::put($lastKnownKey, $weather, now()->addDay());

                return $weather;
            });
        } catch (Throwable $exception) {
            Log::warning('Weather service unavailable: ' . $exception->getMessage());

            return Cache::get($lastKnownKey);
        }
    }

    private function conditionLabel(?int $code): string
    {
        return match ($code) {
            0 => 'Clear sky',
            1 => 'Mainly clear',
            2 => 'Partly cloudy',
            3 => 'Overcast',
            45, 48 => 'Fog',
            51, 53, 55 => 'Drizzle',
            56, 57 => 'Freezing drizzle',
            61, 63, 65 => 'Rain',
            66, 67 => 'Freezing rain',
            71, 73, 75 => 'Snow',
            77 => 'Snow grains',
            80, 81, 82 => 'Rain showers',
            85, 86 => 'Snow showers',
            95 => 'Thunderstorm',
            96, 99 => 'Thunderstorm with hail',
            default => 'Weather unavailable',
        };
    }
}
