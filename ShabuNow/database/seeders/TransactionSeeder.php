<?php

namespace Database\Seeders;

use App\Http\Controllers\Api\TransactionContoller;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\User;
use Database\Factories\TransactionFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orders = Order::where('status', '!=', 'ordering')->get();
        $users = User::where('role', 'staff')->get();
        $users_count = $users->count();
        $status = ['pending','in_queue','ready','done','rejected'];
        foreach ($orders as $order) {
            $index = null;
            if (in_array($order->status, $status)) {
                $index = array_search($order->status, $status);

                if ($index === 4) {
                    $user = $users->get(fake()->numberBetween(0, $users_count -1));
                    TransactionContoller::add($order->id, $user->id, $status[fake()->numberBetween(0,1)], $status[$index]);
                } else {
                    for ($i = 1; $i < $index + 1; $i++) {
                        $user = $users->get(fake()->numberBetween(0, $users_count -1));
                        TransactionContoller::add($order->id, $user->id, $status[$i-1], $status[$i]);
                    }
                }
            }
        }
    }
}
