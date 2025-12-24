<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('predictions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('match_id')->constrained('matches')->cascadeOnDelete();
            $table->unsignedTinyInteger('home_score');
            $table->unsignedTinyInteger('away_score');
            $table->unsignedSmallInteger('points_earned')->default(0);
            $table->boolean('processed')->default(false);
            $table->timestamps();
            
            $table->unique(['user_id', 'match_id']); // One prediction per user per match
            $table->index('processed');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('predictions');
    }
};
