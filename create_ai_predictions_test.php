<?php

use Illuminate\Support\Facades\DB;

// Quick script to create AI predictions directly

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Game;
use App\Services\AiPredictionService;

$service = new AiPredictionService();

// Get first 10 group matches to test
$games = Game::with(['homeTeam', 'awayTeam'])
    ->where('status', 'scheduled')
    ->where('stage', 'group')
    ->whereHas('homeTeam', function($q) {
        $q->where('is_placeholder', false);
    })
    ->whereHas('awayTeam', function($q) {
        $q->where('is_placeholder', false);
    })
    ->take(10)
    ->get();

echo "Creating AI predictions for {$games->count()} matches...\n\n";

foreach ($games as $game) {
    try {
        $prediction = $service->generatePrediction($game);
        echo "✅ {$game->homeTeam->display_name} vs {$game->awayTeam->display_name} → {$prediction->predicted_home_score}-{$prediction->predicted_away_score} ({$prediction->confidence_percentage}%)\n";
    } catch (\Exception $e) {
        echo "❌ Error: {$e->getMessage()}\n";
    }
}

echo "\n✅ Done! Created " . \App\Models\AiPrediction::count() . " AI predictions\n";
