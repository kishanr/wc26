<?php

use App\Models\User;
use App\Models\Game;
use App\Models\Prediction;
use App\Services\ScoringService;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$service = new ScoringService();
$game = Game::find(1);
$game->home_score = 2; // Result: 2-1
$game->away_score = 1;
$game->save();

$scenarios = [
    'Exact (2-1)' => ['h' => 2, 'a' => 1, 'expected' => 5],
    'Diff (3-2)'  => ['h' => 3, 'a' => 2, 'expected' => 3], // Diff +1, Result +1. Wait. 2-1 diff is +1. 3-2 diff is +1. Correct.
    'Outcome (4-0)' => ['h' => 4, 'a' => 0, 'expected' => 1], // Home Win. Diff +4 vs +1.
    'Wrong (1-2)' => ['h' => 1, 'a' => 2, 'expected' => 0], // Away Win.
    'Draw (1-1)' => ['h' => 1, 'a' => 1, 'expected' => 0], // Draw vs Home Win.
];

echo "Testing Scoring Scenarios for Result 2-1:\n";

foreach ($scenarios as $name => $data) {
    $pred = new Prediction();
    $pred->home_score = $data['h'];
    $pred->away_score = $data['a'];

    $points = $service->calculatePoints($pred, $game);
    
    if ($points === $data['expected']) {
        echo "[PASS] $name: Predicted {$data['h']}-{$data['a']} -> $points pts.\n";
    } else {
        echo "[FAIL] $name: Predicted {$data['h']}-{$data['a']} -> Got $points, Expected {$data['expected']}.\n";
    }
}
