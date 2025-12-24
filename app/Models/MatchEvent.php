<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MatchEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'match_id',
        'event_time',
        'extra_time',
        'type',
        'team_id',
        'player_name',
        'player_name_secondary',
        'description',
    ];

    protected $casts = [
        'event_time' => 'integer',
        'extra_time' => 'integer',
    ];

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class, 'match_id');
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Get display time (e.g., "45+2", "90")
     */
    public function getDisplayTimeAttribute(): string
    {
        if ($this->extra_time) {
            return "{$this->event_time}+{$this->extra_time}'";
        }
        return "{$this->event_time}'";
    }

    /**
     * Get icon for event type
     */
    public function getIconAttribute(): string
    {
        return match ($this->type) {
            'goal' => '⚽',
            'own_goal' => '⚽🔄',
            'penalty_scored' => '⚽(P)',
            'penalty_missed' => '❌(P)',
            'yellow_card' => '🟨',
            'red_card' => '🟥',
            'second_yellow' => '🟨🟥',
            'substitution' => '🔄',
            'var_decision' => '📺',
            default => '📋',
        };
    }
}
