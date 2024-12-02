<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('brands')->insert([
            [
                'name' => 'Electronics',
                'description' => 'All kinds of electronic devices and accessories.',
                'contact' => 'electronics, gadgets, devices'
            ],
            [
                'name' => 'Books',
                'description' => 'A wide range of books and literature from various genres.',
                'contact' => 'literature, novels, education'
            ],
            [
                'name' => 'Fashion',
                'description' => 'Clothing, accessories, and fashion items for men and women.',
                'contact' => 'clothing, style, fashion'
            ],
            [
                'name' => 'Home & Garden',
                'description' => 'Furniture, decor, and gardening tools for your home.',
                'contact' => 'furniture, decor, gardening'
            ],
            [
                'name' => 'Sports',
                'description' => 'Sports equipment, apparel, and accessories for various sports.',
                'contact' => 'sports, fitness, outdoor'
            ],
        ]);
    }
}
