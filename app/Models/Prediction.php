<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Prediction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'match_id',
        'home_score',
        'away_score',
        'points_earned',
        'processed',
    ];

    protected $casts = [
        'home_score' => 'integer',
        'away_score' => 'integer',
        'points_earned' => 'integer',
        'processed' => 'boolean',
    ];

    // Scoring constants (Aligned with ScoringService)
    public const POINTS_EXACT = 5;      
    public const POINTS_TOTO_GD = 3;    
    public const POINTS_TOTO = 1;       

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class, 'match_id');
    }

    /**
     * Calculate points for this prediction based on actual result
     */
    public function calculatePoints(): int
    {
        return app(\App\Services\ScoringService::class)->calculatePoints($this, $this->game);
    }

    /**
     * Process this prediction after match finishes
     */
    public function process(): void
    {
        // Allow re-processing but subtract old points first if already processed?
        // For simplicity, let's just ensure we don't double count if already processed.
        if ($this->processed) {
            // Already processed. 
            // If we want to support re-calculation, we'd need to track history.
            // For now, let's assume one-time processing.
            return;
        }

        $points = $this->calculatePoints();
        
        $this->update([
            'points_earned' => $points,
            'processed' => true,
        ]);

        // Update user's global XP
        $this->user->increment('xp_points', $points);

        // Update league pivot scores
        foreach ($this->user->leagues as $league) {
            // Check if user is still in the league and update pivot
            $this->user->leagues()->updateExistingPivot($league->id, [
                'total_score' => $league->pivot->total_score + $points
            ]);
        }
    }

    /**
     * Get predicted result type
     */
    public function getPredictedResultAttribute(): string
    {
        if ($this->home_score > $this->away_score) {
            return 'home_win';
        } elseif ($this->home_score < $this->away_score) {
            return 'away_win';
        }
        return 'draw';
    }
}
