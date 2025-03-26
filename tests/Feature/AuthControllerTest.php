<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use JWTAuth;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase; // pour réinitialise la databz apres chaque test

    /**
     * Test d'inscription utilisateur.
     *
     * @return void
     */
    public function register(Request $request)
    {
        $request->validate([
            "name" => "required|string|max:300",
            "email" => "required|string|max:300|email|unique:users",
            "password" => "required|min:4"
        ]);
    
        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request->password),
        ]);
    
        return response()->json(['message' => 'register successful'], 201);
    }

    /**
     * Test de la connexion utilisateur.
     *
     * @return void
     */
    public function login(Request $request)
    {
        $request->validate([
            "email" => "required|string|max:300|email",
            "password" => "required|min:4"
        ]);
    
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Invalid credentials',
            ], 401);
        }
    
        $user = $request->user();
        $token = $user->createToken('auth_token')->plainTextToken;
    
        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
        ]);
    }

    /**
     * Test de la logout utilisateur.
     *
     * @return void
     */
    public function test_user_can_logout()
    {
        // Crée un utilisateur pour le test
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        // Obtenir le token JWT pour l'user
        $token = auth()->login($user);  // user correcte avec JWT

        // Envoie la requête de logout avec le token d'auth
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/logout');

        // Vérifie que la réponse de logout correcte
        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Successfully logged out', // Vérifie que le message est 'Successfully logged out'
                 ]);
    }
}
