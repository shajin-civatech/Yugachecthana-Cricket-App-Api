<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index()
    {
        return Team::with('players')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'short_name' => 'required|string|max:10',
            'logo' => 'nullable|string',
            'primary_color' => 'nullable|string'
        ]);

        $team = Team::create($validated);
        return response()->json($team, 201);
    }

    public function show($id)
    {
        return Team::with('players')->findOrFail($id);
    }
}
