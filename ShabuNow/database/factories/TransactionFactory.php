<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user_range = User::all()->count();
        $order_range = Order::all()->count();
        return [
            'order_id' => $this->faker->numberBetween(1, $order_range), // Replace with your actual logic
            'user_id' => $this->faker->numberBetween(1, $user_range), // Replace with your actual logic
            'before_status' => $this->faker->randomElement(['pending', 'in_queue', 'ready']),
            'after_status' => $this->faker->randomElement(['in_queue', 'ready','done', 'rejected']),
            'change_date' => $this->faker->dateTimeBetween('-30 days', 'now'),
        ];
    }
}
