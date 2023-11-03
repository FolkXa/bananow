<?php

namespace Database\Seeders;

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
        $order = Order::factory()->count(20)->create();
        foreach (Order::all() as $order) {
            $table = Table::find($order->table_id);
            $table->status = 'used';
            $table->save();

            $user = User::find(fake()->unique()->numberBetween(1,20));
            $user->table_id = $table->id;
            $user->save();
        }
    }
}
