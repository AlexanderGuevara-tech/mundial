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
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('match_id')->constrained('matches')->cascadeOnDelete();
            $table->unsignedTinyInteger('score_home')->default(0);
            $table->unsignedTinyInteger('score_away')->default(0);
            $table->unsignedInteger('points')->default(0);
            $table->timestamps();

            $table->unique(['user_id', 'match_id']);
            $table->index(['match_id', 'points']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('predictions');
    }
};
