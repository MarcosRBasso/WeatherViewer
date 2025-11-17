<?php

namespace App\Presenters;

class WeatherPresenter
{
    public function formatCurrent(array $apiData): array
    {
        return [
            'location_name' => $apiData['location']['name'] ?? null,
            'region'        => $apiData['location']['region'] ?? null,
            'country'       => $apiData['location']['country'] ?? null,
            'localtime'     => $apiData['location']['localtime'] ?? null,

            'temperature'   => $apiData['current']['temperature'] ?? null,
            'feels_like'    => $apiData['current']['feelslike'] ?? null,
            'humidity'      => $apiData['current']['humidity'] ?? null,
            'wind_speed'    => $apiData['current']['wind_speed'] ?? null,
            'description'   => $apiData['current']['weather_descriptions'][0] ?? null,
            'icon'          => $apiData['current']['weather_icons'][0] ?? null,
        ];
    }
}
