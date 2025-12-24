<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MiniPrediction extends Model
{
    use HasFactory;

    protected $fillable = [
        'match_id',
        'question',
        'options',
        'correct_option',
        'expires_at',
        'is_active',
    ];

    protected $casts = [
        'options' => 'array',
        'correct_option' => 'integer',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class, 'match_id');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(MiniPredictionAnswer::class);
    }

    /**
     * Check if mini-prediction is still open
     */
    public function getIsOpenAttribute(): bool
    {
        return $this->is_active && now()->lt($this->expires_at);
    }

    /**
     * Check if mini-prediction has been resolved
     */
    public function getIsResolvedAttribute(): bool
    {
        return $this->correct_option !== null;
    }

    /**
     * Get option statistics
     */
    public function getStatisticsAttribute(): array
    {
        $totalAnswers = $this->answers()->count();
        
        if ($totalAnswers === 0) {
            return [];
        }

        $stats = [];
        foreach ($this->options as $index => $option) {
            $count = $this->answers()->where('selected_option', $index)->count();
            $stats[$index] = [
                'option' => $option,
                'count' => $count,
                'percentage' => round(($count / $totalAnswers) * 100),
            ];
        }

        return $stats;
    }

    /**
     * Resolve the mini-prediction
     */
    public function resolve(int $correctOption, int $pointsPerCorrect = 10): void
    {
        $this->update([
            'correct_option' => $correctOption,
            'is_active' => false,
        ]);

        // Award points to correct answers
        $this->answers()
            ->where('selected_option', $correctOption)
            ->each(function ($answer) use ($pointsPerCorrect) {
                $answer->update(['points_earned' => $pointsPerCorrect]);
                $answer->user->increment('xp_points', $pointsPerCorrect);
            });
    }
}
