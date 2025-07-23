<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'total' => $this->faker->randomFloat(2, 10, 100),
            'comment' => $this->faker->sentence(),
            'status' => $this->faker->randomElement(['pending', 'done', 'cancelled']),
            'created_at' => $this->faker->dateTimeBetween('-30 days', 'now'),
            'updated_at' => now(),
        ];
    }
}
