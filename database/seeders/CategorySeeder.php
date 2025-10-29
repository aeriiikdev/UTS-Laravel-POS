<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Makanan', 'description' => 'Kategori untuk produk makanan', 'is_active' => true],
            ['name' => 'Minuman', 'description' => 'Kategori untuk produk minuman', 'is_active' => true],
            ['name' => 'Snack', 'description' => 'Kategori untuk produk snack dan cemilan', 'is_active' => true],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        $this->command->info('30 categories seeded successfully!');
    }
}