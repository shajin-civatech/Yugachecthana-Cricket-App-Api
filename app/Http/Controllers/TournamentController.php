<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use App\Models\TournamentTeam;
use Illuminate\Http\Request;

class TournamentController extends Controller
{
    public function index()
    {
        return Tournament::with('teams')->orderBy('created_at', 'desc')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'venue' => 'nullable|string',
        ]);

        $tournament = Tournament::create($validated);

        return response()->json($tournament, 201);
    }

    public function show($id)
    {
        return Tournament::with(['teams', 'matches'])->findOrFail($id);
    }

    public function addTeam(Request $request, $id)
    {
        $validated = $request->validate([
            'team_id' => 'required|exists:teams,id',
        ]);

        $tournament = Tournament::findOrFail($id);

        // Attach team if not already attached
        if (!$tournament->teams()->where('team_id', $validated['team_id'])->exists()) {
            $tournament->teams()->attach($validated['team_id']);
        }

        return response()->json(['message' => 'Team added to tournament successfully']);
    }
}
