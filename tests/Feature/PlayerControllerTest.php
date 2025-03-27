<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Tournament;
use App\Models\Player;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tests\TestCase;

class PlayerControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $token;
    protected $tournament;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->token = JWTAuth::fromUser($this->user);
        $this->tournament = Tournament::factory()->create(['created_by' => $this->user->id]);
    }

    public function test_user_can_register_for_tournament()
    {
        $response = $this->postJson("/api/tournaments/{$this->tournament->id}/players", [], [
            'Authorization' => "Bearer $this->token"
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'id', 'user_id', 'tournament_id'
                 ]);
    }

    public function test_user_cannot_register_twice_for_tournament()
    {
        $this->postJson("/api/tournaments/{$this->tournament->id}/players", [], [
            'Authorization' => "Bearer $this->token"
        ]);

        $response = $this->postJson("/api/tournaments/{$this->tournament->id}/players", [], [
            'Authorization' => "Bearer $this->token"
        ]);

        $response->assertStatus(400)
                 ->assertJson([
                     'error' => 'User is already registered for this tournament'
                 ]);
    }

    public function test_user_can_view_all_players_in_tournament()
    {
        $this->postJson("/api/tournaments/{$this->tournament->id}/players", [], [
            'Authorization' => "Bearer $this->token"
        ]);

        $response = $this->getJson("/api/tournaments/{$this->tournament->id}/players", [
            'Authorization' => "Bearer $this->token"
        ]);

        $response->assertStatus(200)
                 ->assertJsonCount(1);
    }

    public function test_user_can_unregister_from_tournament()
    {
        $player = Player::create([
            'user_id' => $this->user->id,
            'tournament_id' => $this->tournament->id,
        ]);

        $response = $this->deleteJson("/api/tournaments/{$this->tournament->id}/players/{$player->id}", [], [
            'Authorization' => "Bearer $this->token"
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Player removed from tournament'
                 ]);

        $this->assertDatabaseMissing('players', ['id' => $player->id]);
    }

    public function test_user_cannot_unregister_if_player_not_found()
    {
        $response = $this->deleteJson("/api/tournaments/{$this->tournament->id}/players/999", [], [
            'Authorization' => "Bearer $this->token"
        ]);

        $response->assertStatus(404)
                 ->assertJson([
                     'error' => 'Player not found in this tournament'
                 ]);
    }
}