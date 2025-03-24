<?php

namespace App\Http\Controllers;

use App\Models\Score;
use App\Models\TournamentMatch;
use Illuminate\Http\Request;

class ScoreController extends Controller
{
    public function store(Request $request, $match_id)
    {
        $request->validate([
            'player_id' => 'required|exists:users,id',
            'score' => 'required|integer|min:0'
        ]);

        $match = TournamentMatch::find($match_id);

        if (!$match) {
            return response()->json(['error' => 'Match not found'], 404);
        }

        $score = Score::create([
            'match_id' => $match_id,
            'player_id' => $request->player_id,
            'score' => $request->score
        ]);

        return response()->json($score, 201);
    }

    public function update(Request $request, $match_id)
    {
        $request->validate([
            'player_id' => 'required|exists:users,id',
            'score' => 'required|integer|min:0'
        ]);

        $score = Score::where('match_id', $match_id)->where('player_id', $request->player_id)->first();

        if (!$score) {
            return response()->json(['error' => 'Score not found'], 404);
        }

        $score->update(['score' => $request->score]);

        return response()->json($score);
    }
}
