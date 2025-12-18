<?php

namespace App\Http\Controllers;

use App\Models\CricketMatch;
use App\Models\MatchBall;
use App\Models\Team;
use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MatchController extends Controller
{
    public function index()
    {
        return CricketMatch::with(['teamA', 'teamB', 'tournament'])->orderBy('date', 'desc')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'team_a_id' => 'required|exists:teams,id',
            'team_b_id' => 'required|exists:teams,id',
            'tournament_id' => 'nullable|exists:tournaments,id',
            'overs' => 'required|integer',
            'date' => 'required|date',
            'venue' => 'nullable|string',
        ]);

        $match = CricketMatch::create($validated);
        return response()->json($match, 201);
    }

    public function show($id)
    {
        return CricketMatch::with(['teamA', 'teamB', 'balls', 'tournament', 'tossWinner', 'winner', 'mom'])->findOrFail($id);
    }

    public function updateScore(Request $request, $id)
    {
        // Complex scoring logic
        $validated = $request->validate([
            'inning' => 'required|string', // team_a or team_b
            'over_number' => 'required|integer',
            'ball_number' => 'required|integer',
            'runs' => 'required|integer', // runs off bat
            'is_wicket' => 'boolean',
            'extras_type' => 'nullable|string', // wide, no_ball, etc
            'extras_runs' => 'integer',
            'bowler_name' => 'nullable|string',
            'batsman_name' => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated, $id) {
            $match = CricketMatch::findOrFail($id);

            // 1. Create Ball Record
            MatchBall::create(array_merge($validated, ['match_id' => $id]));

            // 2. Calculate Totals
            $totalRuns = $validated['runs'] + ($validated['extras_runs'] ?? 0);
            $isWicket = $validated['is_wicket'] ?? false;

            // 3. Update Match Summary
            if ($validated['inning'] == 'team_a') {
                $match->increment('total_runs_a', $totalRuns);
                if ($isWicket) $match->increment('wickets_a');
                // Basic over calculation approximation
                if ($validated['ball_number'] == 6) {
                    $match->increment('overs_played_a', 0.4); // e.g. 0.6 -> +1.0 logic handled in FE usually, or store balls count
                } else {
                    $match->increment('overs_played_a', 0.1);
                }
            } else {
                $match->increment('total_runs_b', $totalRuns);
                if ($isWicket) $match->increment('wickets_b');
                if ($validated['ball_number'] == 6) {
                    $match->increment('overs_played_b', 0.4);
                } else {
                    $match->increment('overs_played_b', 0.1);
                }
            }

            // Update status if needed
            $match->status = 'live';
            $match->save();
        });

        return response()->json(['message' => 'Score updated successfully', 'match' => CricketMatch::find($id)]);
    }
}
