<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'city',
        'state',
        'country',
        'cep',
    ];

    public function weatherRecords()
    {
        return $this->hasMany(WeatherRecord::class);
    }

    public function searchHistories()
    {
        return $this->hasMany(SearchHistory::class);
    }
}
