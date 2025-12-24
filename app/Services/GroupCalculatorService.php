<?php

namespace App\Services;

use App\Models\Game;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Collection;

class GroupCalculatorService
{
    /**
     * Calculate group standings for a user based on their predictions.
     * Returns an array keyed by Group Name (A, B, C...) containing ordered teams.
     */
    public function calculateStandings(User $user): array
    {
        // 1. Fetch all Group Stage matches
        $matches = Game::where('stage', 'group')->get();

        // 2. Fetch User's predictions for these matches
        $predictions = $user->predictions()
            ->whereIn('match_id', $matches->pluck('id'))
            ->get()
            ->keyBy('match_id');

        $groups = [];

        // 3. Initialize groups
        $teams = Team::whereNotNull('group')->get();
        foreach ($teams as $team) {
            $groups[$team->group][$team->id] = [
                'team' => $team,
                'played' => 0,
                'won' => 0,
                'drawn' => 0,
                'lost' => 0,
                'gf' => 0, // Goals For
                'ga' => 0, // Goals Against
                'gd' => 0, // Goal Difference
                'points' => 0,
            ];
        }

        // 4. Process matches
        foreach ($matches as $match) {
            $homeId = $match->home_team_id;
            $awayId = $match->away_team_id;
            $group = $match->group;

            // Use prediction if available, otherwise skip (or use real result if finished?)
            // User requested "when you fill in your predictions", so use predictions.
            if ($predictions->has($match->id)) {
                $pred = $predictions[$match->id];
                $homeScore = $pred->home_score;
                $awayScore = $pred->away_score;

                $this->updateStats($groups[$group][$homeId], $homeScore, $awayScore);
                $this->updateStats($groups[$group][$awayId], $awayScore, $homeScore);
            }
        }

        // 5. Sort groups
        foreach ($groups as $groupName => &$groupTeams) {
            uasort($groupTeams, function ($a, $b) {
                // Points
                if ($a['points'] !== $b['points']) {
                    return $b['points'] <=> $a['points'];
                }
                // Goal Difference
                if ($a['gd'] !== $b['gd']) {
                    return $b['gd'] <=> $a['gd'];
                }
                // Goals For
                return $b['gf'] <=> $a['gf'];
            });
        }

        return $groups;
    }

    private function updateStats(&$stats, $goalsFor, $goalsAgainst)
    {
        $stats['played']++;
        $stats['gf'] += $goalsFor;
        $stats['ga'] += $goalsAgainst;
        $stats['gd'] = $stats['gf'] - $stats['ga'];

        if ($goalsFor > $goalsAgainst) {
            $stats['won']++;
            $stats['points'] += 3;
        } elseif ($goalsFor === $goalsAgainst) {
            $stats['drawn']++;
            $stats['points'] += 1;
        } else {
            $stats['lost']++;
        }
    }

    public function getBestThirds(array $standings): array
    {
        $thirds = [];
        foreach ($standings as $group => $teams) {
            $teams = array_values($teams); // Ensure indexed array
            if (isset($teams[2])) {
                $thirds[] = $teams[2];
            }
        }

        usort($thirds, function ($a, $b) {
            // Points (Higher is better)
            if ($a['points'] !== $b['points']) {
                return $b['points'] <=> $a['points'];
            }
            // Goal Difference (Higher is better)
            if ($a['gd'] !== $b['gd']) {
                return $b['gd'] <=> $a['gd'];
            }
            // Goals For (Higher is better)
            return $b['gf'] <=> $a['gf'];
        });

        return $thirds;
    }
}
