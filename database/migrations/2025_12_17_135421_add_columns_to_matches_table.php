<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->unsignedBigInteger('tournament_id')->nullable()->after('id');
            $table->string('venue')->nullable()->after('overs');
            $table->unsignedBigInteger('toss_winner_id')->nullable();
            $table->string('toss_decision')->nullable(); // bat, bowl
            $table->unsignedBigInteger('winning_team_id')->nullable();
            $table->string('result_type')->nullable(); // normal, tie, no_result
            $table->unsignedBigInteger('man_of_the_match_id')->nullable();

            $table->foreign('tournament_id')->references('id')->on('tournaments')->onDelete('cascade');
            $table->foreign('toss_winner_id')->references('id')->on('teams')->onDelete('set null');
            $table->foreign('winning_team_id')->references('id')->on('teams')->onDelete('set null');
            $table->foreign('man_of_the_match_id')->references('id')->on('players')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->dropForeign(['tournament_id']);
            $table->dropForeign(['toss_winner_id']);
            $table->dropForeign(['winning_team_id']);
            $table->dropForeign(['man_of_the_match_id']);
            $table->dropColumn([
                'tournament_id',
                'venue',
                'toss_winner_id',
                'toss_decision',
                'winning_team_id',
                'result_type',
                'man_of_the_match_id'
            ]);
        });
    }
};
