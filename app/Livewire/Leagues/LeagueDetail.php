<?php

namespace App\Livewire\Leagues;

use App\Models\League;
use Livewire\Component;

class LeagueDetail extends Component
{
    public League $league;

    public function mount(League $league)
    {
        $this->league = $league;
        
        // Ensure user is member
        if (!$league->members()->where('user_id', auth()->id())->exists()) {
            abort(403, 'You are not a member of this league.');
        }
    }

    public function render()
    {
        // Fetch members sorted by total_score (pivot) or global xp?
        // Let's use global XP for now as we don't sync pivot score yet.
        // Actually, for a *private league* it usually tracks *tournament* points globally anyway.
        // If we want league-specific scoring (e.g. starting from 0 when league starts), we need complex sync.
        // For simplicity: League Ranking = Global XP Ranking of members.
        
        $members = $this->league->members()
            ->withCount(['predictions as correct_scores_count' => function($query) {
                $query->where('points_earned', \App\Models\Prediction::POINTS_EXACT);
            }])
            ->orderByPivot('total_score', 'desc')
            ->orderBy('id')
            ->get();

        return view('livewire.leagues.league-detail', [
            'members' => $members
        ]);
    }
}
