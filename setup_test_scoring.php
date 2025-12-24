<?php

use App\Models\User;
use App\Models\Game;
use App\Models\Prediction;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// 1. Ensure Admin
$user = User::first();
if (!$user) {
    echo "No user found!";
    exit(1);
}
$user->is_admin = true;
$user->save();
echo "User {$user->email} is now admin.\n";

// 2. Find Match
$game = Game::find(1);
if (!$game) {
    echo "Game 1 not found.\n";
    exit(1);
}

// 3. Create Prediction (expecting 5 points if result is 2-1)
Prediction::updateOrCreate(
    ['user_id' => $user->id, 'match_id' => $game->id],
    ['home_score' => 2, 'away_score' => 1, 'processed' => false, 'points_earned' => 0]
);

echo "Prediction created/reset for Game 1 (2-1).\n";
