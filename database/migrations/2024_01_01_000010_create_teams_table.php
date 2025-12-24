<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->json('name'); // translatable: {"en": "Netherlands", "nl": "Nederland"}
            $table->string('iso_code', 3)->unique(); // NED, BRA, ARG
            $table->string('flag_url')->nullable();
            $table->char('group', 1)->nullable()->index(); // A, B, C... L
            $table->json('colors')->nullable(); // {"primary": "#FF6600", "secondary": "#FFFFFF"}
            $table->string('confederation', 10)->nullable(); // UEFA, CONMEBOL, etc.
            $table->integer('fifa_ranking')->nullable();
            $table->boolean('is_placeholder')->default(false); // For playoff winners TBD
            $table->string('placeholder_label')->nullable(); // "UEFA Playoff D Winner"
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};
