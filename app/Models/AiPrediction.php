<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiPrediction extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_id',
        'predicted_home_score',
        'predicted_away_score',
        'confidence_percentage',
        'reasoning',
    ];

    protected $casts = [
        'reasoning' => 'array',
    ];

    /**
     * Get the game this prediction is for
     */
    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    /**
     * Get confidence level as text
     */
    public function getConfidenceLevelAttribute(): string
    {
        if ($this->confidence_percentage >= 80) return 'Very High';
        if ($this->confidence_percentage >= 65) return 'High';
        if ($this->confidence_percentage >= 50) return 'Medium';
        return 'Low';
    }

    /**
     * Get predicted result (win/draw/loss for home team)
     */
    public function getPredictedResultAttribute(): string
    {
        if ($this->predicted_home_score > $this->predicted_away_score) return 'home_win';
        if ($this->predicted_home_score < $this->predicted_away_score) return 'away_win';
        return 'draw';
    }
}
