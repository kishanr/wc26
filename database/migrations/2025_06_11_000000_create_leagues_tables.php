<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leagues', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique(); // Invite code
            $table->foreignId('owner_id')->constrained('users')->cascadeOnDelete();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('league_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('league_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->bigInteger('total_score')->default(0); // Cache score for this league
            $table->integer('rank')->nullable(); // Cache rank
            $table->timestamp('joined_at')->useCurrent();
            $table->timestamps();

            $table->unique(['league_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('league_users');
        Schema::dropIfExists('leagues');
    }
};
