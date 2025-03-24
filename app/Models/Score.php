<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    use HasFactory;

    protected $fillable = ['match_id', 'player_id', 'score'];

    public function match()
    {
        return $this->belongsTo(TournamentMatch::class);
    }

    public function player()
    {
        return $this->belongsTo(User::class);
    }
}
