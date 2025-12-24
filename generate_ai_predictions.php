<?php

// Script to generate AI predictions for all scheduled matches

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Force MySQL connection
config(['database.default' => 'mysql']);

use App\Models\Game;
use App\Services\AiPredictionService;

$service = new AiPredictionService();

// Get all scheduled matches
$games = Game::with(['homeTeam', 'awayTeam'])
    ->where('status', 'scheduled')
    ->get();

echo "Generating AI predictions for {$games->count()} matches...\n\n";

$generated = 0;
foreach ($games as $game) {
    try {
        $prediction = $service->generatePrediction($game);
        $generated++;
        
        echo sprintf(
            "✅ %s vs %s → %d-%d (%d%% confident)\n",
            $game->homeTeam->display_name,
            $game->awayTeam->display_name,
            $prediction->predicted_home_score,
            $prediction->predicted_away_score,
            $prediction->confidence_percentage
        );
    } catch (\Exception $e) {
        echo "❌ Failed for game {$game->id}: " . $e->getMessage() . "\n";
    }
}

echo "\n🎉 Successfully generated {$generated} AI predictions!\n";
