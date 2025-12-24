<?php

namespace App\Services;

use App\Models\Game;
use App\Models\Prediction;

class ScoringService
{
    /**
     * Calculate points for a single prediction based on the match result.
     *
     * Rules:
     * - 5 Points: Exact Score (e.g. Pred 2-1, Result 2-1)
     * - 3 Points: Correct Outcome & Goal Difference (e.g. Pred 2-0, Result 3-1)
     * - 1 Point: Correct Outcome Only (e.g. Pred 2-1, Result 4-0)
     * - 0 Points: Incorrect
     */
    public function calculatePoints(Prediction $prediction, Game $match): int
    {
        // If match result not set, no points possible
        if ($match->home_score === null || $match->away_score === null) {
            return 0;
        }

        // 1. Exact Score
        if ($prediction->home_score === $match->home_score && $prediction->away_score === $match->away_score) {
            return 5;
        }

        // Determine Outcomes (1 = Home Win, 0 = Draw, -1 = Away Win)
        $predOutcome = $this->getOutcome($prediction->home_score, $prediction->away_score);
        $matchOutcome = $this->getOutcome($match->home_score, $match->away_score);

        // 2. Incorrect Outcome
        if ($predOutcome !== $matchOutcome) {
            return 0;
        }

        // We know the outcome is correct now. Check Goal Difference.
        $predDiff = $prediction->home_score - $prediction->away_score;
        $matchDiff = $match->home_score - $match->away_score;

        // 3. Correct Outcome & Goal Difference
        // Note: For draws, the diff is always 0, so if you predicted a draw (e.g. 1-1) but result was 2-2, 
        // you get correct outcome (Draw) AND correct diff (0). 
        // Logic: 1-1 vs 2-2. Exact? No. Outcome? Draw (Same). Diff? 0 (Same). -> 3 Points.
        if ($predDiff === $matchDiff) {
            return 3;
        }

        // 4. Correct Outcome Only
        return 1;
    }

    private function getOutcome(int $home, int $away): int
    {
        if ($home > $away) return 1;
        if ($home < $away) return -1;
        return 0;
    }
}
