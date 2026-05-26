<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('matches', function (Blueprint $table) {
            $table->id();
            $table->string('group_name', 2)->index();
            $table->string('team_home');
            $table->string('team_away');
            $table->string('flag_home', 16)->default('');
            $table->string('flag_away', 16)->default('');
            $table->date('match_date')->nullable()->index();
            $table->unsignedTinyInteger('result_home')->nullable();
            $table->unsignedTinyInteger('result_away')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('matches');
    }
};
