<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TournamentController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $tournament = Tournament::create([
            'name' => $request->name,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'created_by' => auth()->id(),
        ]);

        return response()->json($tournament, 201);
    }

    public function index()
    {
        $tournaments = Tournament::all();
        return response()->json($tournaments);
    }

    public function show($id)
    {
        $tournament = Tournament::find($id);

        if (!$tournament) {
            return response()->json(['error' => 'Tournament not found'], 404);
        }

        return response()->json($tournament);
    }

    public function update(Request $request, $id)
    {
        $tournament = Tournament::find($id);

        if (!$tournament) {
            return response()->json(['error' => 'Tournament not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'description' => 'string',
            'start_date' => 'date',
            'end_date' => 'date|after:start_date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $tournament->update($request->only('name', 'description', 'start_date', 'end_date'));

        return response()->json($tournament);
    }

    public function destroy($id)
    {
        $tournament = Tournament::find($id);

        if (!$tournament) {
            return response()->json(['error' => 'Tournament not found'], 404);
        }

        $tournament->delete();
        return response()->json(['message' => 'Tournament deleted successfully']);
    }
}
