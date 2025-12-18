<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'tournament_teams')
            ->withPivot('matches_played', 'won', 'lost', 'tied', 'points', 'nrr')
            ->withTimestamps();
    }

    public function matches()
    {
        return $this->hasMany(CricketMatch::class);
    }
}
