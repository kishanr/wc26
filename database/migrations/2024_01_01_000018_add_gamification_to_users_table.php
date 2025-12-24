<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar_url')->nullable()->after('email');
            $table->foreignId('favorite_team_id')->nullable()->after('avatar_url')->constrained('teams')->nullOnDelete();
            $table->unsignedInteger('xp_points')->default(0)->after('favorite_team_id');
            $table->json('settings')->nullable()->after('xp_points'); // {"lang": "nl", "notifications": true}
            $table->boolean('is_verified')->default(false)->after('settings');
            $table->timestamp('banned_until')->nullable()->after('is_verified');
            $table->boolean('is_admin')->default(false)->after('banned_until');
            
            $table->index('xp_points'); // For leaderboard sorting
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['favorite_team_id']);
            $table->dropColumn([
                'avatar_url',
                'favorite_team_id',
                'xp_points',
                'settings',
                'is_verified',
                'banned_until',
                'is_admin',
            ]);
        });
    }
};
