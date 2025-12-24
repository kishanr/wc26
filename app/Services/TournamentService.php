<?php

namespace App\Services;

use App\Models\Game;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Collection;

class TournamentService
{
    protected $groupCalculator;

    public function __construct(GroupCalculatorService $groupCalculator)
    {
        $this->groupCalculator = $groupCalculator;
    }

    /**
     * Resolve a placeholder team (e.g., 'W-A', 'W73') into a real Team object based on user predictions.
     */
    public function resolveTeam(Team $team, array $standings, array $bestThirds, array $bracketData = []): ?Team
    {
        if (!$team->is_placeholder) {
            return $team;
        }

        $code = $team->iso_code;

        // Handle W73 style (R32 winners)
        if (preg_match('/^W(\d+)$/', $code, $matches)) {
            $matchIdAsRef = (int)$matches[1];
            $index = $matchIdAsRef - 73;
            $winnerId = $bracketData['r32'][$index] ?? null;
            return $winnerId ? Team::find($winnerId) : null;
        }

        // Handle R16 results (R16-1, R16-2... or W89-W96 winners)
        if (preg_match('/^R16-(\d+)$/', $code, $matches)) {
            $index = (int)$matches[1] - 1;
            $winnerId = $bracketData['r16'][$index] ?? null;
            return $winnerId ? Team::find($winnerId) : null;
        }
        
        // Handle QF results
        if (preg_match('/^QF-(\d+)$/', $code, $matches)) {
            $index = (int)$matches[1] - 1;
            $winnerId = $bracketData['qf'][$index] ?? null;
            return $winnerId ? Team::find($winnerId) : null;
        }

        $parts = explode('-', $code);
        if (count($parts) < 2) return null;

        $type = $parts[0]; // W, R, 3
        $suffix = $parts[1]; // A, B, ... or number 1, 2...

        if ($type === 'W' && isset($standings[$suffix])) {
            $first = array_values($standings[$suffix])[0] ?? null; 
            return $first['team'] ?? null;
        }

        if ($type === 'R' && isset($standings[$suffix])) {
            $second = array_values($standings[$suffix])[1] ?? null;
            return $second['team'] ?? null;
        }

        if ($type === '3') {
            $rankIndex = (int)$suffix - 1; // 3-1 -> index 0
            return $bestThirds[$rankIndex]['team'] ?? null;
        }

        return null;
    }

    public function getStandings(User $user): array
    {
        return $this->groupCalculator->calculateStandings($user);
    }

    public function getBestThirds(array $standings): array
    {
        return $this->groupCalculator->getBestThirds($standings);
    }
}
