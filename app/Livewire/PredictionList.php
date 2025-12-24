<?php

namespace App\Livewire;

use App\Models\Game;
use App\Models\Prediction;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('My Predictions')]
class PredictionList extends Component
{
    public function fastPredict($gameId, $homeScore, $awayScore)
    {
        $user = Auth::user();
        $game = Game::find($gameId);

        if (!$game || !$game->predictions_open) {
            session()->flash('error', 'Predictions are closed for this match.');
            return;
        }

        Prediction::updateOrCreate(
            ['user_id' => $user->id, 'match_id' => $gameId],
            [
                'home_score' => $homeScore,
                'away_score' => $awayScore,
            ]
        );

        session()->flash('success', 'Prediction saved!');
    }

    public function render(\App\Services\TournamentService $tournament)
    {
        $user = Auth::user();
        
        $predictions = Prediction::where('user_id', $user->id)
            ->with(['game.homeTeam', 'game.awayTeam', 'game.stadium'])
            ->get();

        $standings = $tournament->getStandings($user);
        $bestThirds = $tournament->getBestThirds($standings);
        $bracketData = $user->bracket?->data ?? [
            'r32' => array_fill(0, 16, null),
            'r16' => array_fill(0, 8, null),
            'qf' => array_fill(0, 4, null),
            'sf' => array_fill(0, 2, null),
            'final' => array_fill(0, 1, null),
        ];

        // Resolve teams for all predictions
        foreach ($predictions as $prediction) {
            $prediction->game->homeTeam = $tournament->resolveTeam($prediction->game->homeTeam, $standings, $bestThirds, $bracketData) ?? $prediction->game->homeTeam;
            $prediction->game->awayTeam = $tournament->resolveTeam($prediction->game->awayTeam, $standings, $bestThirds, $bracketData) ?? $prediction->game->awayTeam;
        }

        $upcoming = $predictions->filter(fn($p) => $p->game->status !== 'finished')
            ->sortBy(fn($p) => $p->game->start_time);
            
        $past = $predictions->filter(fn($p) => $p->game->status === 'finished')
            ->sortByDesc(fn($p) => $p->game->start_time);

        $unpredicted = Game::where('status', 'scheduled')
            ->whereDoesntHave('predictions', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->with(['homeTeam', 'awayTeam'])
            ->orderBy('start_time')
            ->get();

        // Resolve teams for unpredicted matches
        foreach ($unpredicted as $game) {
            $game->homeTeam = $tournament->resolveTeam($game->homeTeam, $standings, $bestThirds, $bracketData) ?? $game->homeTeam;
            $game->awayTeam = $tournament->resolveTeam($game->awayTeam, $standings, $bestThirds, $bracketData) ?? $game->awayTeam;
        }

        // Group unpredicted by stage
        $unpredictedGrouped = $unpredicted->groupBy('stage_name');

        return view('livewire.prediction-list', [
            'upcoming' => $upcoming,
            'past' => $past,
            'unpredictedGrouped' => $unpredictedGrouped,
        ]);
    }
}
