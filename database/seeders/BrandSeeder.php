<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            [
                'name' => 'Apple',
                'description' => 'American multinational technology company that specializes in consumer electronics, software and online services.',
            ],
            [
                'name' => 'Samsung',
                'description' => 'South Korean multinational conglomerate known for electronics, semiconductors, and technology products.',
            ],
            [
                'name' => 'Sony',
                'description' => 'Japanese multinational conglomerate corporation specializing in electronics, gaming, and entertainment.',
            ],
            [
                'name' => 'LG',
                'description' => 'South Korean multinational electronics company producing home appliances, mobile communications, and vehicle components.',
            ],
            [
                'name' => 'Xiaomi',
                'description' => 'Chinese multinational electronics company that designs, develops, and manufactures smartphones, mobile apps, and consumer electronics.',
            ],
        ];

        foreach ($brands as $brandData) {
            Brand::create($brandData);
        }
    }
}