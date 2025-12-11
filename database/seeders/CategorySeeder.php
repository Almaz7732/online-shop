<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Parent categories with their subcategories
        $categories = [
            'Electronics' => [
                'Smartphones',
                'Laptops',
                'Headphones',
                'Cameras',
                'Smart Watches'
            ],
            'Fashion' => [
                'Men\'s Clothing',
                'Women\'s Clothing',
                'Shoes',
                'Accessories',
                'Bags'
            ],
            'Home & Garden' => [
                'Furniture',
                'Kitchen',
                'Garden Tools',
                'Decor',
                'Appliances'
            ],
            'Sports' => [
                'Fitness Equipment',
                'Outdoor Gear',
                'Team Sports',
                'Water Sports',
                'Cycling'
            ],
            'Books' => [
                'Fiction',
                'Non-fiction',
                'Educational',
                'Children\'s Books',
                'E-books'
            ]
        ];

        foreach ($categories as $parentName => $subcategories) {
            // Create parent category
            $parentCategory = Category::create([
                'name' => $parentName,
                'parent_id' => null
            ]);

            // Create subcategories
            foreach ($subcategories as $subcategoryName) {
                Category::create([
                    'name' => $subcategoryName,
                    'parent_id' => $parentCategory->id
                ]);
            }
        }
    }
}