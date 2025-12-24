<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('home_team_id')->constrained('teams')->cascadeOnDelete();
            $table->foreignId('away_team_id')->constrained('teams')->cascadeOnDelete();
            $table->foreignId('stadium_id')->nullable()->constrained('stadiums')->nullOnDelete();
            $table->dateTime('start_time')->index();
            $table->enum('status', ['scheduled', 'live', 'finished', 'postponed', 'cancelled'])->default('scheduled');
            $table->enum('stage', ['group', 'round_of_32', 'round_of_16', 'quarter_final', 'semi_final', 'third_place', 'final'])->default('group');
            $table->char('group', 1)->nullable(); // For group stage matches
            $table->integer('matchday')->nullable(); // Matchday 1, 2, 3 for group stage
            $table->unsignedTinyInteger('home_score')->nullable();
            $table->unsignedTinyInteger('away_score')->nullable();
            $table->unsignedTinyInteger('home_score_penalties')->nullable();
            $table->unsignedTinyInteger('away_score_penalties')->nullable();
            $table->text('ai_analysis')->nullable(); // Pre-match tactical analysis
            $table->string('slug')->unique();
            $table->integer('api_football_id')->nullable()->unique(); // External API reference
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('matches');
    }
};
