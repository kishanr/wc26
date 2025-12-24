<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bracket extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'data',
        'total_points',
        'is_locked',
        'locked_at',
        'completed_at',
    ];

    protected $casts = [
        'data' => 'array',
        'total_points' => 'integer',
        'is_locked' => 'boolean',
        'locked_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if bracket can still be edited
     */
    public function getCanEditAttribute(): bool
    {
        return !$this->is_locked;
    }

    /**
     * Lock the bracket (called when tournament knockout stage begins)
     */
    public function lock(): void
    {
        $this->update([
            'is_locked' => true,
            'locked_at' => now(),
        ]);
    }

    /**
     * Get predicted winner for a specific round position
     */
    public function getWinner(string $round, int $position): ?int
    {
        return $this->data[$round][$position] ?? null;
    }

    /**
     * Set predicted winner for a specific round position
     */
    public function setWinner(string $round, int $position, int $teamId): void
    {
        $data = $this->data;
        $data[$round][$position] = $teamId;
        $this->update(['data' => $data]);
    }

    /**
     * Get the predicted champion
     */
    public function getPredictedChampionAttribute(): ?Team
    {
        $championId = $this->data['final']['winner'] ?? null;
        return $championId ? Team::find($championId) : null;
    }
}
