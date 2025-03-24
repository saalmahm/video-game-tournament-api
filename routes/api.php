<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JWTAuthController;
use App\Http\Controllers\TournamentController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\TournamentMatchController;
use App\Http\Controllers\ScoreController;
use App\Http\Middleware\JwtMiddleware;


Route::post('/register', [JWTAuthController::class, 'register']); 
Route::post('/login', [JWTAuthController::class, 'login']);

Route::middleware('jwtAuth')->group(function () {
    Route::post('/logout', [JWTAuthController::class, 'logout']);
});

Route::middleware('jwtAuth')->group(function () {
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

    Route::post('/matches', [TournamentMatchController::class, 'store']);
    Route::get('/matches', [TournamentMatchController::class, 'index']);
    Route::get('/matches/{id}', [TournamentMatchController::class, 'show']);
    Route::put('/matches/{id}', [TournamentMatchController::class, 'update']);
    Route::delete('/matches/{id}', [TournamentMatchController::class, 'destroy']);

    Route::post('/matches/{id}/scores', [ScoreController::class, 'store']);
    Route::put('/matches/{id}/scores', [ScoreController::class, 'update']);
});