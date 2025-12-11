<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ProdactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            $this->command->info('Starting production data import...');

            // Clear existing data
            $this->clearExistingData();

            // Import data in order
            $this->importBrands();
            $this->importCategories();
            $this->importProducts();

            $this->command->info('Production data import completed successfully!');
        });
    }

    /**
     * Clear existing data
     */
    private function clearExistingData()
    {
        $this->command->info('Clearing existing data...');

        // Disable foreign key checks temporarily
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        ProductImage::truncate();
        Product::truncate();
        Category::truncate();
        Brand::truncate();

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Import brands from JSON
     */
    private function importBrands()
    {
        $this->command->info('Importing brands...');

        $brandsPath = database_path('json/brands.json');
        if (!File::exists($brandsPath)) {
            $this->command->error('Brands JSON file not found!');
            return;
        }

        $brands = json_decode(File::get($brandsPath), true);

        foreach ($brands as $brandData) {
            Brand::create([
                'id' => $brandData['id'],
                'name' => $brandData['title'],
                'slug' => Str::slug($brandData['title']),
            ]);
        }

        $this->command->info('Imported ' . count($brands) . ' brands');
    }

    /**
     * Import categories from JSON
     */
    private function importCategories()
    {
        $this->command->info('Importing categories...');

        $categoriesPath = database_path('json/categories.json');
        if (!File::exists($categoriesPath)) {
            $this->command->error('Categories JSON file not found!');
            return;
        }

        $categories = json_decode(File::get($categoriesPath), true);

        foreach ($categories as $categoryData) {
            Category::create([
                'id' => $categoryData['id'],
                'name' => $categoryData['title'],
                'slug' => $categoryData['slug'],
                'parent_id' => $categoryData['parent_id'],
            ]);
        }

        $this->command->info('Imported ' . count($categories) . ' categories');
    }

    /**
     * Import products from JSON with images
     */
    private function importProducts()
    {
        $this->command->info('Importing products...');

        $productsPath = database_path('json/products.json');
        if (!File::exists($productsPath)) {
            $this->command->error('Products JSON file not found!');
            return;
        }

        $products = json_decode(File::get($productsPath), true);

        foreach ($products as $productData) {
            // Validate required relationships
            if (!Category::find($productData['category_id'])) {
                $this->command->warn("Category {$productData['category_id']} not found for product {$productData['id']}, skipping...");
                continue;
            }

            // Check brand exists (can be null)
            if ($productData['brand_id'] && !Brand::find($productData['brand_id'])) {
                $this->command->warn("Brand {$productData['brand_id']} not found for product {$productData['id']}, setting to null...");
                $productData['brand_id'] = null;
            }

            // Clean description from \n \r characters
            $cleanDescription = null;
            if (!empty($productData['description'])) {
                $cleanDescription = str_replace(['\n', '\r', '\\n', '\\r'], ' ', $productData['description']);
                $cleanDescription = preg_replace('/\s+/', ' ', trim($cleanDescription));
            }

            // Create product
            $product = Product::create([
                'id' => $productData['id'],
                'name' => $productData['title'],
                'slug' => $productData['slug'],
                'description' => $cleanDescription,
                'price' => $productData['price'],
                'category_id' => $productData['category_id'],
                'brand_id' => $productData['brand_id'], // can be null
            ]);

            // Handle product images
            $this->handleProductImages($product, $productData['images'] ?? []);
        }

        $this->command->info('Imported ' . count($products) . ' products');
    }

    /**
     * Handle product images copying and creation
     */
    private function handleProductImages(Product $product, array $images)
    {
        if (empty($images)) {
            return;
        }

        $sourceDir = storage_path("products/{$product->id}");
        if (!File::exists($sourceDir)) {
            $this->command->warn("Images directory not found for product {$product->id}");
            return;
        }

        // Filter only original images (ignore resized versions)
        $originalImages = array_filter($images, function($imagePath) {
            return !str_contains($imagePath, '.210x210_') && !str_contains($imagePath, '.510x510_');
        });

        $imageOrder = 1;
        $isPrimary = true;

        foreach ($originalImages as $imagePath) {
            $fileName = basename($imagePath);
            $sourceFilePath = $sourceDir . '/' . $fileName;

            if (!File::exists($sourceFilePath)) {
                $this->command->warn("Image file not found: {$sourceFilePath}");
                continue;
            }

            // Create target directory
            $targetDir = storage_path('app/public/products');
            if (!File::exists($targetDir)) {
                File::makeDirectory($targetDir, 0755, true);
            }

            // Generate unique filename
            $extension = pathinfo($fileName, PATHINFO_EXTENSION);
            $newFileName = time() . '_' . $product->id . '_' . $imageOrder . '.' . $extension;
            $targetFilePath = $targetDir . '/' . $newFileName;

            // Copy file
            if (File::copy($sourceFilePath, $targetFilePath)) {
                // Create ProductImage record
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => 'products/' . $newFileName,
                    'is_primary' => $isPrimary,
                ]);

                $isPrimary = false; // Only first image is primary
                $imageOrder++;
            } else {
                $this->command->warn("Failed to copy image: {$sourceFilePath}");
            }
        }

        $this->command->info("Processed " . ($imageOrder - 1) . " images for product {$product->name}");
    }
}
