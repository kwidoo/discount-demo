<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'is_admin' => true,
            'is_authenticated' => true,
        ]);

        // Create products from different categories
        $products = [
            Product::factory()->electronics()->create([
                'name' => 'Premium Smartphone',
                'price' => 899.99,
            ]),
            Product::factory()->electronics()->create([
                'name' => 'Wireless Headphones',
                'price' => 149.99,
            ]),
            Product::factory()->clothing()->create([
                'name' => 'Designer Jeans',
                'price' => 79.99,
            ]),
            Product::factory()->clothing()->create([
                'name' => 'Winter Jacket',
                'price' => 129.99,
            ]),
            Product::factory()->books()->create([
                'name' => 'Programming Masterclass',
                'price' => 49.99,
            ]),
        ];

        // Create a cart for the admin user
        $cart = Cart::create(['user_id' => $admin->id]);

        // Add some products to the cart
        $cart->products()->attach([
            $products[0]->id => ['quantity' => 1],
            $products[2]->id => ['quantity' => 2],
            $products[4]->id => ['quantity' => 1],
        ]);

        // Output the created data
        $this->command->info('Sample data created successfully!');
        $this->command->info("Admin user: admin@example.com / password");
        $this->command->info('Products created: ' . count($products));
    }
}
