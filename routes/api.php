<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TournamentController;
use App\Http\Controllers\PlayerController;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/tournaments', [TournamentController::class, 'store']);
    Route::get('/tournaments', [TournamentController::class, 'index']); 
    Route::get('/tournaments/{id}', [TournamentController::class, 'show']); 
    Route::put('/tournaments/{id}', [TournamentController::class, 'update']);
    Route::delete('/tournaments/{id}', [TournamentController::class, 'destroy']); 

    Route::post('/tournaments/{tournament_id}/players', [PlayerController::class, 'store']); 
    Route::get('/tournaments/{tournament_id}/players', [PlayerController::class, 'index']); 
    Route::delete('/tournaments/{tournament_id}/players/{player_id}', [PlayerController::class, 'destroy']); 
});