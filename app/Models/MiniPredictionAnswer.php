<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MiniPredictionAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'mini_prediction_id',
        'user_id',
        'selected_option',
        'points_earned',
    ];

    protected $casts = [
        'selected_option' => 'integer',
        'points_earned' => 'integer',
    ];

    public function miniPrediction(): BelongsTo
    {
        return $this->belongsTo(MiniPrediction::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if answer was correct
     */
    public function getIsCorrectAttribute(): bool
    {
        $miniPrediction = $this->miniPrediction;
        
        if ($miniPrediction->correct_option === null) {
            return false;
        }

        return $this->selected_option === $miniPrediction->correct_option;
    }

    /**
     * Get the selected option text
     */
    public function getSelectedOptionTextAttribute(): string
    {
        $options = $this->miniPrediction->options;
        return $options[$this->selected_option] ?? '';
    }
}
