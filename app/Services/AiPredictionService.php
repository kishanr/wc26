<?php

namespace App\Services;

use App\Models\Game;
use App\Models\AiPrediction;

class AiPredictionService
{
    /**
     * Generate AI prediction for a match
     */
    public function generatePrediction(Game $game): AiPrediction
    {
        $homeTeam = $game->homeTeam;
        $awayTeam = $game->awayTeam;

        // Skip if teams are placeholders
        if ($homeTeam->is_placeholder || $awayTeam->is_placeholder) {
            return $this->createNeutralPrediction($game);
        }

        $factors = $this->calculateFactors($game);
        $scores = $this->calculateScores($factors);
        $confidence = $this->calculateConfidence($factors);

        return AiPrediction::updateOrCreate(
            ['game_id' => $game->id],
            [
                'predicted_home_score' => $scores['home'],
                'predicted_away_score' => $scores['away'],
                'confidence_percentage' => $confidence,
                'reasoning' => $factors['reasoning'],
            ]
        );
    }

    /**
     * Calculate all factors that influence the prediction
     */
    private function calculateFactors(Game $game): array
    {
        $homeTeam = $game->homeTeam;
        $awayTeam = $game->awayTeam;

        // FIFA Ranking factor (40% weight)
        $fifaFactor = $this->calculateFifaFactor($homeTeam->fifa_ranking ?? 50, $awayTeam->fifa_ranking ?? 50);

        // World Cup experience factor (20% weight)
        $wcFactor = $this->calculateWorldCupFactor(
            $homeTeam->world_cup_titles ?? 0,
            $homeTeam->world_cup_appearances ?? 0,
            $awayTeam->world_cup_titles ?? 0,
            $awayTeam->world_cup_appearances ?? 0
        );

        // Home advantage factor (15% weight)
        $homeFactor = $this->calculateHomeAdvantage($homeTeam, $game);

        // Luck/Random factor (10% weight)
        $luckFactor = (rand(0, 100) - 50) / 100; // -0.5 to +0.5

        // Combined strength
        $homeStrength = ($fifaFactor * 0.4) + ($wcFactor * 0.2) + ($homeFactor * 0.15) + ($luckFactor * 0.1);
        $awayStrength = -$homeStrength; // Inverse for away team

        return [
            'home_strength' => $homeStrength,
            'away_strength' => $awayStrength,
            'fifa_factor' => $fifaFactor,
            'wc_factor' => $wcFactor,
            'home_factor' => $homeFactor,
            'luck_factor' => $luckFactor,
            'reasoning' => [
                'fifa_ranking_diff' => ($awayTeam->fifa_ranking ?? 50) - ($homeTeam->fifa_ranking ?? 50),
                'wc_titles_diff' => ($homeTeam->world_cup_titles ?? 0) - ($awayTeam->world_cup_titles ?? 0),
                'home_advantage' => $homeFactor > 0,
            ],
        ];
    }

    /**
     * Calculate FIFA ranking factor
     */
    private function calculateFifaFactor(int $homeRanking, int $awayRanking): float
    {
        $diff = $awayRanking - $homeRanking;
        // Normalize: -1 (home much better) to +1 (away much better)
        return max(-1, min(1, $diff / 50));
    }

    /**
     * Calculate World Cup experience factor
     */
    private function calculateWorldCupFactor(int $homeTitles, int $homeApps, int $awayTitles, int $awayApps): float
    {
        $homeExp = ($homeTitles * 3) + ($homeApps * 0.5);
        $awayExp = ($awayTitles * 3) + ($awayApps * 0.5);
        $diff = $homeExp - $awayExp;
        return max(-1, min(1, $diff / 20));
    }

    /**
     * Calculate home advantage
     */
    private function calculateHomeAdvantage($homeTeam, Game $game): float
    {
        // CONCACAF teams get advantage in North America
        if ($homeTeam->confederation === 'CONCACAF') {
            return 0.3;
        }
        // Small advantage for all home teams
        return 0.15;
    }

    /**
     * Convert strength factors to actual scores
     */
    private function calculateScores(array $factors): array
    {
        $homeStrength = $factors['home_strength'];
        
        // Base scores
        $homeScore = 1;
        $awayScore = 1;

        // Adjust based on strength
        if ($homeStrength > 0.5) {
            // Strong home favorite
            $homeScore = rand(2, 3);
            $awayScore = rand(0, 1);
        } elseif ($homeStrength > 0.2) {
            // Moderate home favorite
            $homeScore = rand(1, 2);
            $awayScore = rand(0, 1);
        } elseif ($homeStrength < -0.5) {
            // Strong away favorite
            $homeScore = rand(0, 1);
            $awayScore = rand(2, 3);
        } elseif ($homeStrength < -0.2) {
            // Moderate away favorite
            $homeScore = rand(0, 1);
            $awayScore = rand(1, 2);
        } else {
            // Close match
            $homeScore = rand(0, 2);
            $awayScore = rand(0, 2);
        }

        return [
            'home' => $homeScore,
            'away' => $awayScore,
        ];
    }

    /**
     * Calculate confidence percentage
     */
    private function calculateConfidence(array $factors): int
    {
        $strength = abs($factors['home_strength']);
        
        // Higher strength difference = higher confidence
        if ($strength > 0.7) return rand(75, 85);
        if ($strength > 0.5) return rand(65, 75);
        if ($strength > 0.3) return rand(55, 65);
        return rand(45, 55);
    }

    /**
     * Create neutral prediction for placeholder teams
     */
    private function createNeutralPrediction(Game $game): AiPrediction
    {
        return AiPrediction::updateOrCreate(
            ['game_id' => $game->id],
            [
                'predicted_home_score' => 1,
                'predicted_away_score' => 1,
                'confidence_percentage' => 50,
                'reasoning' => ['note' => 'Neutral prediction - teams not yet determined'],
            ]
        );
    }
}
