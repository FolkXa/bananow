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
        Order::factory()->count(10)->create();
        $orders = Order::all();
        $menus = Menu::all();
        $status = ['making', 'ready'];
        foreach ($orders as $order) {
            $rand = fake()->numberBetween(1,5);
            for ($i = 0; $i < $rand; $i++) {
                $menu = $menus->find(fake()->numberBetween(1, $menus->count()));
                if (!$order->menus()->find($menu->id)) {
                    $order->menus()->attach($menu->id);
                }
                $order->menus()->updateExistingPivot($menu->id, ['quantity' => fake()->numberBetween(1,5)]);
                if ($order->status === 'in_queue') {
                    $order->menus()->updateExistingPivot($menu->id, ['status' => $status[0]]);
                } elseif ($order->status === 'ready' && $order->status !== 'done') {
                    $order->menus()->updateExistingPivot($menu->id, ['status' => $status[1]]);
                }
            }
        }
    }
}
