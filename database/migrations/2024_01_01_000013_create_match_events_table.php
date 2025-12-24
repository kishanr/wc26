<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('match_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('match_id')->constrained('matches')->cascadeOnDelete();
            $table->unsignedTinyInteger('event_time'); // Minute of the event
            $table->unsignedTinyInteger('extra_time')->nullable(); // 90+3, 45+2, etc.
            $table->enum('type', ['goal', 'own_goal', 'penalty_scored', 'penalty_missed', 'yellow_card', 'red_card', 'second_yellow', 'substitution', 'var_decision']);
            $table->foreignId('team_id')->nullable()->constrained('teams')->nullOnDelete();
            $table->string('player_name')->nullable();
            $table->string('player_name_secondary')->nullable(); // Assist or substituted player
            $table->string('description')->nullable();
            $table->timestamps();
            
            $table->index(['match_id', 'event_time']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('match_events');
    }
};
