<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UserSeeder::class);
        $this->call(MenuSeeder::class);
        $this->call(OrderSeeder::class);
        $this->call(TransactionSeeder::class);
        // \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'username' => 'มาลี สวยมาก',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'firstname' => 'มาลี',
            'lastname' => 'สวยมาก'
        ]);
        \App\Models\User::factory()->create([
            'username' => 'Staff ทำขนม',
            'email' => 'staff@example.com',
            'password' => Hash::make('password'),
            'role' => 'staff'
        ]);
        \App\Models\User::factory()->create([
            'username' => 'Admin เองจ้า',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);
    }
}
