<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Admin',
            'email' => 'admin@pos.com',
            'password' => Hash::make('password'),
        ]);

        // Create Cashier User
        User::create([
            'name' => 'Kasir 1',
            'email' => 'kasir@pos.com',
            'password' => Hash::make('password'),
        ]);

        // Call CategorySeeder
        $this->call([
            CategorySeeder::class,
        ]);

        // Create 50 Products
        $products = [
            // Makanan (Category 1)
            ['category_id' => 1, 'code' => 'PRD-001', 'name' => 'Nasi Goreng', 'description' => 'Nasi goreng spesial dengan telur', 'price' => 25000, 'stock' => 50, 'is_active' => true],
            ['category_id' => 1, 'code' => 'PRD-002', 'name' => 'Mie Ayam', 'description' => 'Mie ayam dengan bakso', 'price' => 20000, 'stock' => 40, 'is_active' => true],
            ['category_id' => 1, 'code' => 'PRD-003', 'name' => 'Ayam Goreng', 'description' => 'Ayam goreng crispy', 'price' => 30000, 'stock' => 30, 'is_active' => true],
            ['category_id' => 1, 'code' => 'PRD-004', 'name' => 'Sate Ayam', 'description' => 'Sate ayam 10 tusuk dengan bumbu kacang', 'price' => 28000, 'stock' => 35, 'is_active' => true],
            
            // Minuman (Category 2)
            ['category_id' => 2, 'code' => 'PRD-005', 'name' => 'Es Teh Manis', 'description' => 'Es teh manis segar', 'price' => 5000, 'stock' => 100, 'is_active' => true],
            ['category_id' => 2, 'code' => 'PRD-006', 'name' => 'Kopi Hitam', 'description' => 'Kopi hitam tanpa gula', 'price' => 8000, 'stock' => 80, 'is_active' => true],
            ['category_id' => 2, 'code' => 'PRD-007', 'name' => 'Jus Jeruk', 'description' => 'Jus jeruk segar asli', 'price' => 12000, 'stock' => 60, 'is_active' => true],
            ['category_id' => 2, 'code' => 'PRD-008', 'name' => 'Air Mineral', 'description' => 'Air mineral kemasan 600ml', 'price' => 3000, 'stock' => 200, 'is_active' => true],
            
            // Snack (Category 3)
            ['category_id' => 3, 'code' => 'PRD-009', 'name' => 'Keripik Kentang', 'description' => 'Keripik kentang rasa original', 'price' => 15000, 'stock' => 75, 'is_active' => true],
            ['category_id' => 3, 'code' => 'PRD-010', 'name' => 'Coklat Batangan', 'description' => 'Coklat batangan premium', 'price' => 10000, 'stock' => 90, 'is_active' => true],
            ['category_id' => 3, 'code' => 'PRD-011', 'name' => 'Biskuit', 'description' => 'Biskuit krim vanilla', 'price' => 8000, 'stock' => 100, 'is_active' => true],
            ['category_id' => 3, 'code' => 'PRD-012', 'name' => 'Kacang Goreng', 'description' => 'Kacang goreng renyah pedas', 'price' => 12000, 'stock' => 85, 'is_active' => true],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }

        $this->command->info('Database seeded successfully!');
        $this->command->info('Total Categories: 3');
        $this->command->info('Total Products: 12');
        $this->command->info('Admin Login: admin@pos.com / password');
        $this->command->info('Kasir Login: kasir@pos.com / password');
    }
}