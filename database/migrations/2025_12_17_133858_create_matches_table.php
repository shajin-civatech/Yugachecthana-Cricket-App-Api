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
        Schema::create('matches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('team_a_id');
            $table->unsignedBigInteger('team_b_id');
            $table->integer('overs')->default(20);
            $table->string('status')->default('scheduled'); // scheduled, live, completed
            $table->string('batting_team')->nullable(); // 'team_a' or 'team_b'

            // Team A Stats
            $table->integer('total_runs_a')->default(0);
            $table->integer('wickets_a')->default(0);
            $table->float('overs_played_a')->default(0.0);

            // Team B Stats
            $table->integer('total_runs_b')->default(0);
            $table->integer('wickets_b')->default(0);
            $table->float('overs_played_b')->default(0.0);

            $table->timestamps();

            $table->foreign('team_a_id')->references('id')->on('teams')->onDelete('cascade');
            $table->foreign('team_b_id')->references('id')->on('teams')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matches');
    }
};
