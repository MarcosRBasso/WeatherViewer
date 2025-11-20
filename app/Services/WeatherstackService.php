<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WeatherstackService
{
    public function getCurrentWeather(string $city): ?array
    {
        $key = config('services.weatherstack.key');

        if (!$key) {
            return null;
        }

        try {
            $response = Http::withoutVerifying()
                ->get('http://api.weatherstack.com/current', [
                    'access_key' => $key,
                    'query'      => $city,
                    'units'      => 'm',
                ]);

            if ($response->failed()) {
                return null;
            }

            $json = $response->json();

            if (isset($json['success']) && $json['success'] === false) {
                return null;
            }

            return $json;
        } catch (\Throwable $e) {
            logger()->error('Erro ao consultar Weatherstack', ['exception' => $e->getMessage()]);
            return null;
        }
    }
}
