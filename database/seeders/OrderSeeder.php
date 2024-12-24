<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Order::factory(50)->create()->each(function ($order) {
            $products = Product::query()->inRandomOrder()->take(rand(1, 3))->get();

            $order->products()->attach($products->pluck('id'));
        });
    }
}
