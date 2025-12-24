<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class League extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'owner_id',
        'description',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($league) {
            if (empty($league->code)) {
                $league->code = strtoupper(Str::random(8));
            }
        });
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'league_users')
            ->withPivot(['total_score', 'rank', 'joined_at'])
            ->withCasts(['joined_at' => 'datetime'])
            ->withTimestamps()
            ->orderByPivot('total_score', 'desc');
    }
}
