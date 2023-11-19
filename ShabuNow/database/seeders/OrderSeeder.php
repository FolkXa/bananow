<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Order;
use App\Models\Table;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Order::factory()->count(40)->create();
        $orders = Order::all();
        $menus = Menu::all();
        foreach ($orders as $order) {
            $rand = fake()->numberBetween(1,5);
            for ($i = 0; $i < $rand; $i++) {
                $menu = $menus->find(fake()->numberBetween(1, $menus->count()));
                if (!$order->menus()->find($menu->id)) {
                    $order->menus()->attach($menu->id);
                }
                $order->menus()->updateExistingPivot($menu->id, ['quantity' => fake()->numberBetween(1,5)]);
            }
        }
    }
}
