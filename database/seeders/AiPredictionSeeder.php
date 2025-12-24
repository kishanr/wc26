<?php

namespace Database\Seeders;

use App\Models\Game;
use App\Services\AiPredictionService;
use Illuminate\Database\Seeder;

class AiPredictionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $service = new AiPredictionService();
        
        // Get all scheduled matches (not finished or live)
        $games = Game::with(['homeTeam', 'awayTeam'])
            ->where('status', 'scheduled')
            ->get();

        $this->command->info("Generating AI predictions for {$games->count()} matches...");
        
        $generated = 0;
        foreach ($games as $game) {
            try {
                $prediction = $service->generatePrediction($game);
                $generated++;
                
                $this->command->line(sprintf(
                    "✅ %s vs %s → %d-%d (%d%% confident)",
                    $game->homeTeam->display_name,
                    $game->awayTeam->display_name,
                    $prediction->predicted_home_score,
                    $prediction->predicted_away_score,
                    $prediction->confidence_percentage
                ));
            } catch (\Exception $e) {
                $this->command->error("Failed for game {$game->id}: " . $e->getMessage());
            }
        }

        $this->command->info("\n🎉 Successfully generated {$generated} AI predictions!");
    }
}
