<?php

use App\Models\Game;
use Illuminate\Support\Str;

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$games = Game::with(['homeTeam', 'awayTeam'])->get();

foreach ($games as $game) {
    if (!$game->slug) {
        $homeName = $game->homeTeam->name ?? 'TBD';
        $awayName = $game->awayTeam->name ?? 'TBD';
        
        $base = Str::slug($homeName . '-vs-' . $awayName);
        $slug = $base . '-' . $game->id; // Ensure uniqueness simply
        
        $game->update(['slug' => $slug]);
        echo "Updated Game {$game->id}: {$slug}\n";
    }
}

echo "Done.\n";
