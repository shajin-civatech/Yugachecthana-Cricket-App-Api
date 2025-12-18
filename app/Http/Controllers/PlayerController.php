<?php

namespace App\Http\Controllers;

use App\Models\Player;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    public function index(Request $request)
    {
        $query = Player::with('team');
        if ($request->has('team_id')) {
            $query->where('team_id', $request->team_id);
        }
        return $query->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'team_id' => 'nullable|exists:teams,id',
            'role' => 'nullable|string',
            'batting_style' => 'nullable|string',
            'bowling_style' => 'nullable|string',
        ]);

        $player = Player::create($validated);
        return response()->json($player, 201);
    }

    public function show($id)
    {
        return Player::with('team')->findOrFail($id);
    }
}
