<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar_url',
        'favorite_team_id',
        'xp_points',
        'settings',
        'is_verified',
        'banned_until',
        'is_admin',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'settings' => 'array',
            'is_verified' => 'boolean',
            'banned_until' => 'datetime',
            'is_admin' => 'boolean',
            'xp_points' => 'integer',
        ];
    }

    /**
     * Required for FilamentPHP admin access
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->is_admin;
    }

    /**
     * User's favorite teams
     */
    public function favoriteTeams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class, 'team_user');
    }

    /**
     * User's favorite team (Deprecated)
     * @deprecated Use favoriteTeams() instead
     */
    public function favoriteTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'favorite_team_id');
    }

    /**
     * User's predictions
     */
    public function predictions(): HasMany
    {
        return $this->hasMany(Prediction::class);
    }

    /**
     * User's bracket
     */
    public function bracket(): HasOne
    {
        return $this->hasOne(Bracket::class);
    }

    /**
     * Leagues the user is a member of
     */
    public function leagues(): BelongsToMany
    {
        return $this->belongsToMany(League::class, 'league_users')
            ->withPivot(['total_score', 'rank', 'joined_at'])
            ->withCasts(['joined_at' => 'datetime'])
            ->withTimestamps();
    }

    /**
     * Leagues owned by user
     */
    public function ownedLeagues(): HasMany
    {
        return $this->hasMany(League::class, 'owner_id');
    }

    /**
     * Mini-prediction answers
     */
    public function miniPredictionAnswers(): HasMany
    {
        return $this->hasMany(MiniPredictionAnswer::class);
    }

    /**
     * Check if user is banned
     */
    public function getIsBannedAttribute(): bool
    {
        return $this->banned_until !== null && $this->banned_until->isFuture();
    }

    /**
     * Get user's preferred language
     */
    public function getLanguageAttribute(): string
    {
        return $this->settings['lang'] ?? 'en';
    }

    /**
     * Get total points (alias for xp_points)
     */
    public function getTotalPointsAttribute(): int
    {
        return $this->xp_points;
    }

    /**
     * Get global rank
     */
    public function getGlobalRankAttribute(): int
    {
        return static::where('xp_points', '>', $this->xp_points)->count() + 1;
    }
}
