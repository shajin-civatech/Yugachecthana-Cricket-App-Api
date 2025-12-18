<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TournamentController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\MatchController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Auth
Route::post('/auth/google', [AuthController::class, 'googleLogin']);
Route::post('/auth/verify-otp', [AuthController::class, 'verifyOtp']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Tournaments
Route::apiResource('tournaments', TournamentController::class);
Route::post('tournaments/{id}/add-team', [TournamentController::class, 'addTeam']);

// Teams
Route::apiResource('teams', TeamController::class);

// Players
Route::apiResource('players', PlayerController::class);

// Matches
Route::apiResource('matches', MatchController::class);
Route::post('matches/{id}/score', [MatchController::class, 'updateScore']);
