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
        Schema::create('match_balls', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('match_id');
            $table->string('inning'); // 'team_a' or 'team_b'
            $table->integer('over_number');
            $table->integer('ball_number');

            // Details
            $table->integer('runs')->default(0); // 0, 1, 2, 3, 4, 6
            $table->boolean('is_wicket')->default(false);
            $table->string('extras_type')->nullable(); // wide, no_ball, bye, leg_bye
            $table->integer('extras_runs')->default(0);

            $table->string('bowler_name')->nullable();
            $table->string('batsman_name')->nullable();

            $table->timestamps();

            $table->foreign('match_id')->references('id')->on('matches')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('match_balls');
    }
};
