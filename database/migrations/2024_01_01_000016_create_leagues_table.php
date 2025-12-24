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
            $table->string('invite_code', 8)->unique();
            $table->foreignId('owner_id')->constrained('users')->cascadeOnDelete();
            $table->string('description')->nullable();
            $table->string('image_url')->nullable();
            $table->boolean('is_public')->default(false);
            $table->unsignedSmallInteger('max_members')->default(50);
            $table->timestamps();
        });

        Schema::create('league_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('league_id')->constrained('leagues')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedInteger('total_score')->default(0);
            $table->unsignedSmallInteger('rank')->nullable();
            $table->timestamp('joined_at')->useCurrent();
            $table->timestamps();
            
            $table->unique(['league_id', 'user_id']);
            $table->index(['league_id', 'total_score']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('league_users');
        Schema::dropIfExists('leagues');
    }
};
