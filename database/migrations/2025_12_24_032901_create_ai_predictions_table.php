<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ai_predictions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_id')->constrained()->onDelete('cascade');
            $table->integer('predicted_home_score');
            $table->integer('predicted_away_score');
            $table->integer('confidence_percentage'); // 0-100
            $table->json('reasoning')->nullable(); // Factors that influenced the prediction
            $table->timestamps();
            
            $table->unique('game_id'); // One AI prediction per game
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_predictions');
    }
};
