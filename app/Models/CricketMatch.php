<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CricketMatch extends Model
{
    use HasFactory;

    protected $table = 'matches';
    protected $guarded = [];

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    public function teamA()
    {
        return $this->belongsTo(Team::class, 'team_a_id');
    }

    public function teamB()
    {
        return $this->belongsTo(Team::class, 'team_b_id');
    }

    public function balls()
    {
        return $this->hasMany(MatchBall::class, 'match_id');
    }

    public function tossWinner()
    {
        return $this->belongsTo(Team::class, 'toss_winner_id');
    }

    public function winner()
    {
        return $this->belongsTo(Team::class, 'winning_team_id');
    }

    public function mom()
    {
        return $this->belongsTo(Player::class, 'man_of_the_match_id');
    }
}
