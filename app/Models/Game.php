<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Game extends Model
{
    use HasFactory;

    protected $table = 'matches'; // Keep DB table name as 'matches'

    protected $fillable = [
        'home_team_id',
        'away_team_id',
        'stadium_id',
        'start_time',
        'status',
        'stage',
        'group',
        'matchday',
        'home_score',
        'away_score',
        'home_score_penalties',
        'away_score_penalties',
        'ai_analysis',
        'slug',
        'api_football_id',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    protected $casts = [
        'start_time' => 'datetime',
        'home_score' => 'integer',
        'away_score' => 'integer',
        'home_score_penalties' => 'integer',
        'away_score_penalties' => 'integer',
        'matchday' => 'integer',
    ];

    protected $appends = [
        'status_badge',
        'time_until',
        'stage_name',
        'title',
        'score_display',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($game) {
            if (empty($game->slug)) {
                $game->slug = Str::slug(
                    $game->homeTeam->iso_code . '-vs-' . $game->awayTeam->iso_code . '-' . $game->start_time->format('Y-m-d')
                );
            }
        });
    }

    public function homeTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'home_team_id');
    }

    public function awayTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'away_team_id');
    }

    public function stadium(): BelongsTo
    {
        return $this->belongsTo(Stadium::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(MatchEvent::class, 'match_id')->orderBy('event_time');
    }

    public function predictions(): HasMany
    {
        return $this->hasMany(Prediction::class, 'match_id');
    }

    public function miniPredictions(): HasMany
    {
        return $this->hasMany(MiniPrediction::class, 'match_id');
    }

    /**
     * Check if game is live
     */
    public function getIsLiveAttribute(): bool
    {
        return $this->status === 'live';
    }

    /**
     * Check if game has finished
     */
    public function getIsFinishedAttribute(): bool
    {
        return $this->status === 'finished';
    }

    /**
     * Check if predictions are still open (5 min before start)
     */
    public function getPredictionsOpenAttribute(): bool
    {
        return $this->status === 'scheduled' 
            && now()->lt($this->start_time->subMinutes(5));
    }

    /**
     * Get the game title
     */
    public function getTitleAttribute(): string
    {
        return "{$this->homeTeam->display_name} vs {$this->awayTeam->display_name}";
    }

    /**
     * Get the final score display
     */
    public function getScoreDisplayAttribute(): string
    {
        if ($this->status === 'scheduled') {
            return 'vs';
        }

        $score = "{$this->home_score} - {$this->away_score}";
        
        if ($this->home_score_penalties !== null) {
            $score .= " ({$this->home_score_penalties}-{$this->away_score_penalties} pen)";
        }

        return $score;
    }

    /**
     * Scope for upcoming games
     */
    public function scopeUpcoming($query)
    {
        return $query->where('status', 'scheduled')
            ->where('start_time', '>', now())
            ->orderBy('start_time');
    }

    /**
     * Scope for live games
     */
    public function scopeLive($query)
    {
        return $query->where('status', 'live');
    }

    /**
     * Scope for finished games
     */
    public function scopeFinished($query)
    {
        return $query->where('status', 'finished')
            ->orderBy('start_time', 'desc');
    }
    /**
     * Get badge status for frontend
     */
    public function getStatusBadgeAttribute(): array
    {
        return match ($this->status) {
            'live' => ['class' => 'badge-live', 'text' => 'LIVE'],
            'finished' => ['class' => 'badge-finished', 'text' => 'FT'],
            default => ['class' => 'badge-upcoming', 'text' => $this->time_until],
        };
    }

    /**
     * Get human readable time until match
     */
    public function getTimeUntilAttribute(): string
    {
        $now = now();
        $start = $this->start_time;

        if ($start->isPast()) {
            return 'Soon';
        }

        $diff = $now->diff($start);

        if ($diff->days > 0) {
            return $diff->days . 'd';
        }

        if ($diff->h > 0) {
            return $diff->h . 'h ' . $diff->i . 'm';
        }

        return $diff->i . 'm';
    }

    /**
     * Get display friendly stage name
     */
    public function getStageNameAttribute(): string
    {
        return match ($this->stage) {
            'group' => 'Group ' . $this->group,
            'round_of_32' => 'Round of 32',
            'round_of_16' => 'Round of 16',
            'quarter_final' => 'Quarter Final',
            'semi_final' => 'Semi Final',
            'third_place' => '3rd Place',
            'final' => '🏆 Final',
            default => ucfirst($this->stage),
        };
    }
}
