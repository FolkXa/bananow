<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Menu>
 */
class MenuFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = array('available','outofstock');
        return [
            'name' => fake()->name(),
            'category_id' => Category::find(rand(1, 5)),
            'description' => fake()->realTextBetween(120,200,2),
            'status' => $status[array_rand($status)],
            'price' => fake()->numberBetween(20,200),
        ];
    }
}
