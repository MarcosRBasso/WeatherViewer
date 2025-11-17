<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SearchHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'location_id',
        'searched_at',
        'source',
        'result_snapshot',
    ];

    protected $casts = [
        'searched_at' => 'datetime',
        'result_snapshot' => 'array',
    ];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
