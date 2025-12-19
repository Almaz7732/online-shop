<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CarouselSlide;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CarouselSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Starting carousel slides import...');

        // Clear existing carousel slides
        CarouselSlide::truncate();
        $this->command->info('Cleared existing carousel slides.');

        // Source directory with carousel images
        $sourceDir = storage_path('carousel');

        if (!File::exists($sourceDir)) {
            $this->command->error("Carousel directory not found at: {$sourceDir}");
            return;
        }

        // Ensure public carousel directory exists
//        Storage::makeDirectory('public/carousel');

        // Get all image files from source directory
        $imageFiles = File::files($sourceDir);
        $supportedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        $order = 1;

        foreach ($imageFiles as $file) {
            $extension = strtolower($file->getExtension());

            if (!in_array($extension, $supportedExtensions)) {
                $this->command->warn("Skipping unsupported file: {$file->getFilename()}");
                continue;
            }

            try {
                // Generate new filename
                $filename = time() . '_' . Str::random(8) . '.' . $extension;
                $destinationPath = 'carousel/' . $filename;

                // Copy file to public storage
                $fileContent = File::get($file->getPathname());
                Storage::disk('public')->put($destinationPath, $fileContent);

                // Generate slide data based on filename
                $slideData = $this->generateSlideData($file->getFilename(), $destinationPath, $order);

                // Create carousel slide record
                CarouselSlide::create($slideData);

                $this->command->info("Imported slide: {$file->getFilename()} -> {$filename}");
                $order++;

            } catch (\Exception $e) {
                $this->command->error("Failed to import {$file->getFilename()}: " . $e->getMessage());
            }
        }

        $this->command->info('Carousel slides import completed!');
    }

    /**
     * Generate carousel slide data based on filename and image
     */
    private function generateSlideData(string $filename, string $imagePath, int $order): array
    {
        // Extract product/brand name from filename
        $name = $this->extractNameFromFilename($filename);

        return [
            'image_path' => $imagePath,
            'order' => $order,
            'is_active' => true
        ];
    }

    /**
     * Extract readable name from filename
     */
    private function extractNameFromFilename(string $filename): string
    {
        // Remove extension and clean filename
        $name = pathinfo($filename, PATHINFO_FILENAME);

        // Extract brand/product name before the first underscore or dot
        if (strpos($name, '_') !== false) {
            $name = explode('_', $name)[0];
        }

        // Clean and format name
        $name = str_replace(['-', '_', '.'], ' ', $name);
        $name = preg_replace('/\s+/', ' ', $name);
        $name = trim($name);

        // Capitalize first letter
        return ucfirst($name);
    }
}
