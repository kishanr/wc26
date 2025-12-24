<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Stadium extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'city',
        'country',
        'capacity',
        'latitude',
        'longitude',
        'timezone',
        'image_url',
    ];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'capacity' => 'integer',
    ];

    /**
     * Get all games at this stadium
     */
    public function games(): HasMany
    {
        return $this->hasMany(Game::class);
    }

    /**
     * Get formatted location string
     */
    public function getLocationAttribute(): string
    {
        return "{$this->city}, {$this->country}";
    }
}
