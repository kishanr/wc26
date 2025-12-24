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
        Schema::table('teams', function (Blueprint $table) {
            $table->string('coach')->nullable()->after('fifa_ranking');
            $table->string('captain')->nullable()->after('coach');
            $table->integer('world_cup_titles')->default(0)->after('captain');
            $table->integer('world_cup_appearances')->default(0)->after('world_cup_titles');
            $table->json('team_stats')->nullable()->after('world_cup_appearances');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->dropColumn(['coach', 'captain', 'world_cup_titles', 'world_cup_appearances', 'team_stats']);
        });
    }
};
