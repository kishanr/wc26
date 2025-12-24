<?php

namespace App\Livewire\Admin;

use App\Models\Game;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Artisan;

class MatchManager extends Component
{
    use WithPagination;

    public $search = '';
    
    // For editing
    public $editingGameId = null;
    public $homeScore;
    public $awayScore;
    public $homePens;
    public $awayPens;

    public function mount()
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Unauthorized action.');
        }
    }
    
    public function render()
    {
        $matches = Game::with(['homeTeam', 'awayTeam'])
            ->when($this->search, function ($query) {
                $query->whereHas('homeTeam', fn($q) => $q->where('name', 'like', '%'.$this->search.'%'))
                      ->orWhereHas('awayTeam', fn($q) => $q->where('name', 'like', '%'.$this->search.'%'));
            })
            ->orderBy('start_time', 'asc') // Show upcoming/recent matches
            ->paginate(20);

        return view('livewire.admin.match-manager', [
            'matches' => $matches
        ]);
    }

    public function edit($gameId)
    {
        $game = Game::find($gameId);
        if (!$game) return;

        $this->editingGameId = $game->id;
        $this->homeScore = $game->home_score;
        $this->awayScore = $game->away_score;
        $this->homePens = $game->home_score_penalties;
        $this->awayPens = $game->away_score_penalties;
    }

    public function cancel()
    {
        $this->editingGameId = null;
    }

    public function save($gameId)
    {
        $game = Game::find($gameId);
        if (!$game) return;

        $this->validate([
            'homeScore' => 'required|integer|min:0',
            'awayScore' => 'required|integer|min:0',
            'homePens' => 'nullable|integer|min:0',
            'awayPens' => 'nullable|integer|min:0',
        ]);

        if ($game->id !== $this->editingGameId) {
            return;
        }

        $game->update([
            'home_score' => $this->homeScore,
            'away_score' => $this->awayScore,
            'home_score_penalties' => $this->homePens,
            'away_score_penalties' => $this->awayPens,
            'status' => 'finished' // Auto-finish on save
        ]);

        // Trigger Scoring
        Artisan::call('wc26:calculate-scores', ['game_id' => $game->id]);
        
        session()->flash('success', "Match updated and scores calculated!");
        $this->editingGameId = null;
    }
}
