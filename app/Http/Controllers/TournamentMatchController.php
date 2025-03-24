<?php

namespace App\Http\Controllers;

use App\Models\TournamentMatch;
use App\Models\Tournament;
use App\Models\User;
use Illuminate\Http\Request;

class TournamentMatchController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'tournament_id' => 'required|exists:tournaments,id',
            'player1_id' => 'required|exists:users,id',
            'player2_id' => 'required|exists:users,id'
        ]);

        $match = TournamentMatch::create([
            'tournament_id' => $request->tournament_id,
            'player1_id' => $request->player1_id,
            'player2_id' => $request->player2_id,
            'status' => 'pending'
        ]);

        return response()->json($match, 201);
    }

    public function index()
    {
        return response()->json(TournamentMatch::all());
    }

    public function show($id)
    {
        $match = TournamentMatch::with(['player1', 'player2', 'scores'])->find($id);

        if (!$match) {
            return response()->json(['error' => 'Match not found'], 404);
        }

        return response()->json($match);
    }

    public function update(Request $request, $id)
    {
        $match = TournamentMatch::find($id);

        if (!$match) {
            return response()->json(['error' => 'Match not found'], 404);
        }

        $match->update($request->only('status'));
        return response()->json($match);
    }

    public function destroy($id)
    {
        $match = TournamentMatch::find($id);

        if (!$match) {
            return response()->json(['error' => 'Match not found'], 404);
        }

        $match->delete();
        return response()->json(['message' => 'Match deleted successfully']);
    }
}
