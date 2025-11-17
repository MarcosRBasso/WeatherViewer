<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeatherRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'location_id',
        'date',
        'description',
        'temperature',
        'feels_like',
        'humidity',
        'wind_speed',
        'raw_response',
    ];

    protected $casts = [
        'raw_response' => 'array',
        'date' => 'date',
    ];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
