<?php

namespace Database\Factories;

use App\Models\Player;
use App\Models\User;
use App\Models\Tournament;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlayerFactory extends Factory
{
    protected $model = Player::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(), 
            'tournament_id' => Tournament::factory(), 
        ];
    }
}
