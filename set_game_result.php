<?php

use App\Models\User;
use App\Models\Game;
use App\Models\Prediction;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$game = Game::find(1);
if (!$game) {
    echo "Game 1 not found.\n";
    exit(1);
}

// Set Result to 2-1 (Mexico wins)
$game->home_score = 2;
$game->away_score = 1;
$game->status = 'finished';
$game->save();

echo "Game 1 updated to Finished 2-1.\n";
