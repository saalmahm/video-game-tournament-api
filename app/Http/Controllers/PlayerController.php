<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\Tournament;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
  public function store(Request $request, $tournament_id)
    {
        $tournament = Tournament::find($tournament_id);

        if (!$tournament) {
            return response()->json(['error' => 'Tournament not found'], 404);
        }

        if (Player::where('user_id', auth()->id())->where('tournament_id', $tournament_id)->exists()) {
            return response()->json(['error' => 'User is already registered for this tournament'], 400);
        }

        $player = Player::create([
            'user_id' => auth()->id(),
            'tournament_id' => $tournament_id,
        ]);

        return response()->json($player, 201);
    }

    public function index($tournament_id)
    {
        $tournament = Tournament::find($tournament_id);

        if (!$tournament) {
            return response()->json(['error' => 'Tournament not found'], 404);
        }

        $players = $tournament->players;
        return response()->json($players);
    }

    public function destroy($tournament_id, $player_id)
    {
        $player = Player::where('tournament_id', $tournament_id)->where('id', $player_id)->first();
    
        if (!$player) {
            return response()->json([
                'error' => 'Player not found in this tournament',
                'debug' => Player::all()
            ], 404);
        }
    
        $player->delete();
        return response()->json(['message' => 'Player removed from tournament']);
    }
    
}

