<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Tournament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tymon\JWTAuth\Facades\JWTAuth;
use PHPUnit\Framework\Attributes\Test; // Ajout de l'import

class TournamentControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $token;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->token = JWTAuth::fromUser($this->user);
    }

    #[Test]
    public function user_can_create_tournament()
    {
        $response = $this->postJson('/api/tournaments', [
            'name' => 'FIFA Tournament',
            'description' => 'Un tournoi de FIFA 2025',
            'start_date' => now()->addDays(5)->toDateTimeString(),
            'end_date' => now()->addDays(10)->toDateTimeString(),
        ], ['Authorization' => "Bearer $this->token"]);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'id', 'name', 'description', 'start_date', 'end_date', 'created_by'
                 ]);
    }

    #[Test]
    public function user_can_view_all_tournaments()
    {
        // Créer 3 tournois
        Tournament::factory()->count(3)->create(['created_by' => $this->user->id]);
    
        // Teste si l'API retourne bien 3 tournois
        $response = $this->getJson('/api/tournaments', ['Authorization' => "Bearer $this->token"]);
    
        $response->assertStatus(200)
                 ->assertJsonCount(3);  // Vérifie qu'il y a 3 tournois
    }
    

    #[Test]
    public function user_can_view_a_single_tournament()
    {
        $tournament = Tournament::factory()->create(['created_by' => $this->user->id]);

        $response = $this->getJson("/api/tournaments/{$tournament->id}", ['Authorization' => "Bearer $this->token"]);

        $response->assertStatus(200)
                 ->assertJson([
                     'id' => $tournament->id,
                     'name' => $tournament->name,
                     'description' => $tournament->description,
                 ]);
    }

    #[Test]
    public function user_can_update_tournament()
    {
        $tournament = Tournament::factory()->create(['created_by' => $this->user->id]);

        $response = $this->putJson("/api/tournaments/{$tournament->id}", [
            'name' => 'Tournoi FIFA Ultimate',
        ], ['Authorization' => "Bearer $this->token"]);

        $response->assertStatus(200)
                 ->assertJson([
                     'id' => $tournament->id,
                     'name' => 'Tournoi FIFA Ultimate',
                 ]);
    }

    #[Test]
    public function user_can_delete_tournament()
    {
        $tournament = Tournament::factory()->create(['created_by' => $this->user->id]);

        $response = $this->deleteJson("/api/tournaments/{$tournament->id}", [], ['Authorization' => "Bearer $this->token"]);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Tournament deleted successfully']);

        $this->assertDatabaseMissing('tournaments', ['id' => $tournament->id]);
    }
}
