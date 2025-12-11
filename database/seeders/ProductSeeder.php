<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Get all subcategories (categories with parent_id)
        $subcategories = Category::whereNotNull('parent_id')->get();
        $brands = Brand::all();

        // Predefined product data for realistic names
        $productData = [
            // Electronics
            ['name' => 'iPhone 14 Pro Max', 'price' => 1099, 'description' => 'Latest iPhone with advanced camera system and A16 Bionic chip'],
            ['name' => 'Samsung Galaxy S23 Ultra', 'price' => 1199, 'description' => 'Premium Android smartphone with S Pen and 200MP camera'],
            ['name' => 'MacBook Pro 16-inch', 'price' => 2399, 'description' => 'Professional laptop with M2 Pro chip and Liquid Retina XDR display'],
            ['name' => 'Dell XPS 13', 'price' => 999, 'description' => 'Ultra-portable laptop with InfinityEdge display'],
            ['name' => 'Sony WH-1000XM5', 'price' => 399, 'description' => 'Industry-leading wireless noise canceling headphones'],
            ['name' => 'Apple AirPods Pro', 'price' => 249, 'description' => 'Active noise cancellation and spatial audio'],
            ['name' => 'Canon EOS R6 Mark II', 'price' => 2499, 'description' => 'Full-frame mirrorless camera with advanced autofocus'],
            ['name' => 'Apple Watch Series 8', 'price' => 399, 'description' => 'Advanced health monitoring and fitness tracking'],

            // Fashion
            ['name' => 'Levi\'s 501 Original Jeans', 'price' => 89, 'description' => 'Classic straight fit jeans with authentic details'],
            ['name' => 'Nike Air Force 1', 'price' => 90, 'description' => 'Iconic basketball shoes with timeless style'],
            ['name' => 'Ray-Ban Aviator Classic', 'price' => 154, 'description' => 'Legendary sunglasses with crystal lenses'],
            ['name' => 'Coach Signature Handbag', 'price' => 295, 'description' => 'Luxury leather handbag with signature pattern'],
            ['name' => 'Adidas Ultraboost 22', 'price' => 180, 'description' => 'Premium running shoes with boost technology'],
            ['name' => 'Zara Wool Blend Coat', 'price' => 129, 'description' => 'Elegant winter coat with modern cut'],

            // Home & Garden
            ['name' => 'IKEA HEMNES Bed Frame', 'price' => 199, 'description' => 'Solid wood bed frame with classic design'],
            ['name' => 'KitchenAid Stand Mixer', 'price' => 379, 'description' => 'Professional-grade stand mixer for baking'],
            ['name' => 'Dyson V15 Detect', 'price' => 749, 'description' => 'Cordless vacuum with laser dust detection'],
            ['name' => 'Weber Genesis II Gas Grill', 'price' => 599, 'description' => 'Premium gas grill for outdoor cooking'],
            ['name' => 'Philips Hue Smart Bulbs', 'price' => 49, 'description' => 'Smart LED bulbs with millions of colors'],

            // Sports
            ['name' => 'Peloton Bike+', 'price' => 2495, 'description' => 'Interactive fitness bike with live classes'],
            ['name' => 'North Face Base Camp Duffel', 'price' => 149, 'description' => 'Durable travel bag for outdoor adventures'],
            ['name' => 'Wilson Pro Staff Tennis Racket', 'price' => 199, 'description' => 'Professional tennis racket used by pros'],
            ['name' => 'Patagonia Torrentshell Jacket', 'price' => 129, 'description' => 'Waterproof jacket for outdoor activities'],
            ['name' => 'Trek Domane Road Bike', 'price' => 1999, 'description' => 'Lightweight carbon road bike for cycling enthusiasts'],

            // Books
            ['name' => 'The Great Gatsby', 'price' => 12, 'description' => 'Classic American novel by F. Scott Fitzgerald'],
            ['name' => 'Atomic Habits', 'price' => 18, 'description' => 'Self-help book about building good habits'],
            ['name' => 'Introduction to Algorithms', 'price' => 89, 'description' => 'Comprehensive computer science textbook'],
            ['name' => 'Harry Potter Box Set', 'price' => 58, 'description' => 'Complete collection of Harry Potter books'],
            ['name' => 'Kindle Oasis E-reader', 'price' => 249, 'description' => 'Premium e-reader with adjustable warm light'],
            ['name' => 'The Psychology of Money', 'price' => 16, 'description' => 'Behavioral finance and investing insights']
        ];

        foreach ($productData as $index => $product) {
            Product::create([
                'name' => $product['name'],
                'description' => $product['description'],
                'price' => $product['price'],
                'category_id' => $subcategories->random()->id,
                'brand_id' => $brands->random()->id,
            ]);
        }
    }
}