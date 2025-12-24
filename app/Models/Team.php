<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'iso_code',
        'flag_url',
        'group',
        'colors',
        'confederation',
        'fifa_ranking',
        'is_placeholder',
        'placeholder_label',
    ];

    protected $casts = [
        'name' => 'array',
        'colors' => 'array',
        'is_placeholder' => 'boolean',
    ];

    protected $appends = [
        'display_name',
    ];

    /**
     * Get the team's display name in the current locale
     */
    public function getDisplayNameAttribute(): string
    {
        $locale = app()->getLocale();
        $name = $this->name;
        
        return $name[$locale] ?? $name['en'] ?? array_values($name)[0] ?? '';
    }

    /**
     * Get a specific translation of an attribute
     */
    public function getTranslation(string $attribute, string $locale): string
    {
        $value = $this->{$attribute};
        
        if (!is_array($value)) {
            return $value ?? '';
        }
        
        return $value[$locale] ?? $value['en'] ?? array_values($value)[0] ?? '';
    }

    /**
     * Get all matches where this team plays at home
     */
    public function homeMatches(): HasMany
    {
        return $this->hasMany(Game::class, 'home_team_id');
    }

    /**
     * Get all matches where this team plays away
     */
    public function awayMatches(): HasMany
    {
        return $this->hasMany(Game::class, 'away_team_id');
    }

    /**
     * Get all matches for this team
     */
    public function getAllMatchesAttribute()
    {
        return Game::where('home_team_id', $this->id)
            ->orWhere('away_team_id', $this->id)
            ->orderBy('start_time')
            ->get();
    }

    /**
     * Users who support this team
     */
    public function fans(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'team_user');
    }

    /**
     * Users who support this team (Deprecated)
     */
    public function supporters(): HasMany
    {
        return $this->hasMany(User::class, 'favorite_team_id');
    }
}
