<?php

namespace Database\Factories;

use App\Models\Tournament;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TournamentFactory extends Factory
{
    protected $model = Tournament::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'start_date' => $this->faker->dateTimeBetween('+1 days', '+2 weeks'),
            'end_date' => $this->faker->dateTimeBetween('+3 weeks', '+1 month'),
            'created_by' => \App\Models\User::factory(),
        ];
    }
}
