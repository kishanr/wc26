<?php

namespace App\Livewire;

use App\Models\Bracket;
use App\Models\Team;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Tournament Bracket')]
class BracketBuilder extends Component
{
    public $bracketData = [];
    public $isLocked = false;

    public function mount()
    {
        $user = Auth::user();
        $bracket = $user->bracket;

        if (!$bracket) {
            // Initialize empty bracket structure if none exists
            $this->bracketData = [
                'r32' => array_fill(0, 16, null), // 16 matches (R32 winners)
                'r16' => array_fill(0, 8, null), // 8 matches (R16 winners)
                'qf' => array_fill(0, 4, null), // 4 matches (QF winners)
                'sf' => array_fill(0, 2, null), // 2 matches (SF winners)
                'final' => array_fill(0, 1, null), // 1 match (Final winner)
                'champion' => null,
            ];
        } else {
            $this->bracketData = $bracket->data;
            // Ensure new keys exist if loading old data format
            if (!isset($this->bracketData['r32'])) {
                $this->bracketData['r32'] = array_fill(0, 16, null);
            }
            if (!isset($this->bracketData['r16'])) { // Rename octofinals -> r16 if needed or keep both?
                 // Let's migrate/normalize if keys differ, but for now just use standard keys
                 // If old data used 'octofinals', we might need to map it.
                 // Assuming fresh start since we reseeded.
            }
            $this->isLocked = $bracket->is_locked;
        }
    }

    public function advanceTeam($round, $index, $teamId)
    {
        if ($this->isLocked) return;

        // 1. Set the winner for the current match
        $this->bracketData[$round][$index] = $teamId;

        // 2. Clear subsequent rounds if a change occurred (optional, but good for consistency)
        // actually, let's keep it simple: just update. 
        // If I change R32 winner, the R16 slot automatically updates in view because it reads from this data.
        // But if I already picked a winner in R16, and I change the R32 winner that fed into it...
        // Strictly speaking, that R16 pick becomes invalid if it was the OLD team. 
        // But let's handle that UI-side or just let it be overridden.

        // 3. Auto-save? Or wait for save button.
        // Let's just update state.
    }

    public function saveBracket()
    {
        if ($this->isLocked) {
            return;
        }

        $user = Auth::user();
        
        $user->bracket()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'data' => $this->bracketData,
                'updated_at' => now(),
            ]
        );

        $this->dispatch('notify', message: 'Bracket saved successfully!');
    }

    public function render(\App\Services\TournamentService $tournament)
    {
        $r32Matches = \App\Models\Game::where('stage', 'round_of_32')
            ->with(['homeTeam', 'awayTeam'])
            ->orderBy('id')
            ->get();
        
        $allTeams = Team::all();

        // Calculate Group Standings
        $standings = $tournament->getStandings(Auth::user());
        $bestThirds = $tournament->getBestThirds($standings);
        
        // Map placeholders to predicted teams for ALL knockout matches
        $knockoutMatches = \App\Models\Game::whereIn('stage', [
            'round_of_32', 'round_of_16', 'quarter_final', 'semi_final', 'final'
        ])->orderBy('id')->get();

        $predictedTeams = [];
        foreach ($knockoutMatches as $match) {
            foreach (['home', 'away'] as $side) {
                $team = $side === 'home' ? $match->homeTeam : $match->awayTeam;
                if ($team && $team->is_placeholder) {
                    $predictedTeam = $tournament->resolveTeam($team, $standings, $bestThirds, $this->bracketData);
                    if ($predictedTeam) {
                        $predictedTeams[$team->id] = $predictedTeam;
                    }
                }
            }
        }

        // Build pairings for the UI
        $r16Matches = $knockoutMatches->where('stage', 'round_of_16')->values();
        $pairings = [
            'r16' => $r16Matches->map(fn($m) => [
                'home' => $this->getMatchIndexFromCode($m->homeTeam->iso_code),
                'away' => $this->getMatchIndexFromCode($m->awayTeam->iso_code),
            ]),
        ];

        return view('livewire.bracket-builder', [
            'r32Matches' => $r32Matches,
            'teams' => $allTeams,
            'predictedTeams' => $predictedTeams,
            'pairings' => $pairings,
        ]);
    }

    private function getMatchIndexFromCode($code)
    {
        if (preg_match('/^W(\d+)$/', $code, $matches)) {
            return (int)$matches[1] - 73;
        }
        return null;
    }
}
