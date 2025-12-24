<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mini_predictions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('match_id')->constrained('matches')->cascadeOnDelete();
            $table->string('question'); // "Who scores first?"
            $table->json('options'); // ["Messi", "Mbappé", "Neither"]
            $table->unsignedTinyInteger('correct_option')->nullable(); // Index of correct answer
            $table->dateTime('expires_at');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['match_id', 'is_active']);
        });

        Schema::create('mini_prediction_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mini_prediction_id')->constrained('mini_predictions')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedTinyInteger('selected_option');
            $table->unsignedSmallInteger('points_earned')->default(0);
            $table->timestamps();
            
            $table->unique(['mini_prediction_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mini_prediction_answers');
        Schema::dropIfExists('mini_predictions');
    }
};
