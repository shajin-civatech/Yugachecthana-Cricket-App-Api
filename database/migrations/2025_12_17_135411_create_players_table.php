<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(); // Link to registered user
            $table->unsignedBigInteger('team_id')->nullable(); // Current team
            $table->string('name');
            $table->string('role')->default('All-rounder'); // Batsman, Bowler, All-rounder, Wicket-keeper
            $table->string('batting_style')->nullable(); // Right-hand bat, Left-hand bat
            $table->string('bowling_style')->nullable(); // Right-arm fast, etc.
            $table->string('profile_image')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('team_id')->references('id')->on('teams')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};
