<?php

namespace Database\Factories;

use App\Models\Menu;
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
        $status = array('ordering','pending','in_queue','ready','done', 'rejected');
        $users = User::all();
        return [
            'detail' => fake()->realTextBetween(5,30,2),
            'status' => $status[array_rand($status)],
            'user_id' => $users->get(fake()->numberBetween(0,$users->count() -1))->id,
            'receiving_time' => $this->faker->dateTimeBetween('-1 days', 'now'),
            'order_date' => $this->faker->dateTimeBetween('-3 days', 'now')
        ];
    }
}
