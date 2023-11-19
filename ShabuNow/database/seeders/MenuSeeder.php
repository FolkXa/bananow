<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
//        $menu = Menu::factory()->count(20)->create();
        Menu::create([
            'name' => 'กล้วยทอด',
            'imgPath' => '/images/menus/1700365119.jpg',
            'description' => '5 ชิ้น',
            'status' => 'available',
            'price' => 10,
        ]);

        Menu::create([
            'name' => 'มันทอด',
            'imgPath' => '/images/menus/1700365393.jpg',
            'description' => '5 ชิ้น',
            'status' => 'available',
            'price' => 10,
        ]);

        Menu::create([
            'name' => 'กล้วยตากทอด',
            'imgPath' => '/images/menus/1700365545.jpg',
            'description' => '7 ชิ้น',
            'status' => 'available',
            'price' => 10,
        ]);

        Menu::create([
            'name' => 'ไข่หงส์',
            'imgPath' => '/images/menus/1700365625.jpg',
            'description' => '3 ลูก',
            'status' => 'available',
            'price' => 10,
        ]);

        Menu::create([
            'name' => 'สับปะรดกวน',
            'imgPath' => '/images/menus/1700365774.jpg',
            'description' => '200 กรัม',
            'status' => 'available',
            'price' => 20,
        ]);

        Menu::create([
            'name' => 'กล้วย',
            'imgPath' => '/images/menus/1700365910.jpg',
            'description' => '1 หวี',
            'status' => 'outofstock',
            'price' => 30,
        ]);
    }
}
