<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;

class ProductImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all();

        // Sample image paths (using existing product images from the template)
        $sampleImages = [
            'products/01.jpg',
            'products/02.jpg',
            'products/03.jpg',
            'products/04.jpg',
            'products/05.jpg',
        ];

        foreach ($products as $product) {
            // Random number of images per product (1-3)
            $imageCount = rand(1, 3);

            for ($i = 0; $i < $imageCount; $i++) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $sampleImages[array_rand($sampleImages)],
                    'is_primary' => $i === 0, // First image is primary
                ]);
            }
        }
    }
}
