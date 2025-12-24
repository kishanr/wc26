<?php

namespace App\Console\Commands;

use App\Models\Game;
use App\Models\Prediction;
use App\Services\ScoringService;
use Illuminate\Console\Command;

class CalculateScores extends Command
{
    protected $signature = 'wc26:calculate-scores {game_id}';
    protected $description = 'Calculate points for all predictions of a specific game';

    public function handle()
    {
        $gameId = $this->argument('game_id');
        $game = Game::find($gameId);

        if (!$game) {
            $this->error('Game not found.');
            return;
        }

        if ($game->status !== 'finished') {
            $this->warn("Game is not marked as finished (Status: {$game->status}). Calculating anyway...");
        }

        $this->info("Calculating scores for Game: {$game->homeTeam->display_name} vs {$game->awayTeam->display_name}");

        $predictions = Prediction::where('match_id', $gameId)->get();
        $bar = $this->output->createProgressBar($predictions->count());

        foreach ($predictions as $prediction) {
            $prediction->process();
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Scores calculated successfully.');
    }
}
