<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->insert([
            [
                'category_id' => 1, // ID de una categoría existente en la tabla categories
                'name' => 'Laptop UltraPro',
                'wholesale_price' => 700.00,
                'brand_id' => 1, // ID de una marca existente en la tabla brands
                'sell_price' => 850.00,
                'buy_price' => 650.00,
                'bar_code' => 1234567890123,
                'stock' => 50,
                'description' => 'High-performance laptop with latest technology features.',
                'state' => 'ACTIVO'
            ],
            [
                'category_id' => 2,
                'name' => 'Running Shoes MaxSpeed',
                'wholesale_price' => 50.00,
                'brand_id' => 2,
                'sell_price' => 75.00,
                'buy_price' => 45.00,
                'bar_code' => 1234567890124,
                'stock' => 120,
                'description' => 'Comfortable and durable running shoes designed for athletes.',
                'state' => 'ACTIVO'
            ],
            [
                'category_id' => 3,
                'name' => 'Wireless Earbuds ProSound',
                'wholesale_price' => 30.00,
                'brand_id' => 3,
                'sell_price' => 50.00,
                'buy_price' => 28.00,
                'bar_code' => 1234567890125,
                'stock' => 200,
                'description' => 'High-quality wireless earbuds with noise-cancelling feature.',
                'state' => 'ACTIVO'
            ],
            [
                'category_id' => 4,
                'name' => 'Smartphone SnapX',
                'wholesale_price' => 300.00,
                'brand_id' => 4,
                'sell_price' => 450.00,
                'buy_price' => 290.00,
                'bar_code' => 1234567890126,
                'stock' => 80,
                'description' => 'Feature-packed smartphone with impressive camera and battery life.',
                'state' => 'INACTIVO'
            ],
            [
                'category_id' => 5,
                'name' => '4K Ultra HD TV',
                'wholesale_price' => 400.00,
                'brand_id' => 5,
                'sell_price' => 600.00,
                'buy_price' => 390.00,
                'bar_code' => 1234567890127,
                'stock' => 30,
                'description' => 'Ultra HD TV with 4K resolution and smart features.',
                'state' => 'ACTIVO'
            ]
        ]);
    }
}
