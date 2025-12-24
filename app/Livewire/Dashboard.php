<?php

namespace App\Livewire;

use App\Models\Game;
use App\Models\Team;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Matches')]
class Dashboard extends Component
{
    public $selectedStage = 'all';
    public $selectedGroup = 'A';

    public function setStage($stage)
    {
        $this->selectedStage = $stage;
    }

    public function setGroup($group)
    {
        $this->selectedGroup = $group;
    }

    protected function getRegion($city)
    {
        $regions = [
            'Arlington' => 'Texas',
            'Houston' => 'Texas',
            'Miami Gardens' => 'Florida',
            'Atlanta' => 'Georgia',
            'Philadelphia' => 'Pennsylvania',
            'Seattle' => 'Washington',
            'Foxborough' => 'Massachusetts',
            'Kansas City' => 'Missouri',
            'Santa Clara' => 'California',
            'Inglewood' => 'California',
            'East Rutherford' => 'New Jersey',
            'Mexico City' => 'CDMX',
            'Guadalajara' => 'Jalisco',
            'Monterrey' => 'Nuevo León',
            'Toronto' => 'Ontario',
            'Vancouver' => 'British Columbia',
        ];

        return $regions[$city] ?? '';
    }
    public function render()
    {
        $allMatches = Game::with(['homeTeam', 'awayTeam', 'stadium'])
            ->orderBy('start_time')
            ->get()
            ->map(function ($match) {
                return [
                    'id' => $match->id,
                    'slug' => $match->slug,
                    'stage' => $match->stage,
                    'group' => $match->group,
                    'start_time' => $match->start_time->toIso8601String(),
                    'search_date' => $match->start_time->format('F j Y') . ' ' . 
                                     $match->start_time->format('j F Y') . ' ' . // e.g. "16 June 2026"
                                     $match->start_time->format('d-m-Y') . ' ' . 
                                     $match->start_time->format('d/m'), 
                    'status' => $match->status,
                    'home_score' => $match->home_score,
                    'away_score' => $match->away_score,
                    'stage_name' => $match->stage_name,
                    'status_badge' => $match->status_badge,
                    'home_team' => [
                        'id' => $match->homeTeam->id,
                        'display_name' => $match->homeTeam->display_name,
                        'iso_code' => $match->homeTeam->iso_code,
                        'flag_url' => $match->homeTeam->flag_url,
                    ],
                    'away_team' => [
                        'id' => $match->awayTeam->id,
                        'display_name' => $match->awayTeam->display_name,
                        'iso_code' => $match->awayTeam->iso_code,
                        'flag_url' => $match->awayTeam->flag_url,
                    ],
                    'stadium' => $match->stadium ? [
                        'name' => $match->stadium->name,
                        'city' => $match->stadium->city,
                        'country' => $match->stadium->country,
                        'region' => $this->getRegion($match->stadium->city), // For "Texas" search
                    ] : null,
                ];
            });

        // Get today's/upcoming matches for the hero section
        $now = Carbon::now();
        $todayMatches = Game::with(['homeTeam', 'awayTeam', 'stadium'])
            ->whereDate('start_time', $now->toDateString())
            ->orderBy('start_time')
            ->get();

        // If no matches today, get next upcoming
        if ($todayMatches->isEmpty()) {
            $todayMatches = Game::with(['homeTeam', 'awayTeam', 'stadium'])
                ->where('start_time', '>', $now)
                ->orderBy('start_time')
                ->take(3)
                ->get();
        }

        // Stats
        $totalMatches = Game::count();
        $completedMatches = Game::where('status', 'finished')->count();
        $totalTeams = Team::where(function ($q) {
            $q->where('is_placeholder', false)->orWhereNull('is_placeholder');
        })->count();

        return view('livewire.dashboard', [
            'allMatches' => $allMatches,
            'todayMatches' => $todayMatches,
            'stats' => [
                'total' => $totalMatches,
                'completed' => $completedMatches,
                'teams' => $totalTeams,
            ],
        ]);
    }
}
