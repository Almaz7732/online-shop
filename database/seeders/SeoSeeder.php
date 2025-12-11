<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SeoSetting;

class SeoSeeder extends Seeder
{
    private $name;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->name = config('app.name', 'Example');

        $this->command->info('Starting SEO settings seeding...');

        // Clear existing SEO settings
        SeoSetting::truncate();
        $this->command->info('Cleared existing SEO settings.');

        // Define default SEO settings
        $defaultSeoSettings = [
            [
                'page_type' => 'home',
                'page_identifier' => null,
                'meta_title' => "{$this->name} - Магазин техники и гаджетов",
                'meta_description' => 'Интернет-магазин электроники и гаджетов. Смартфоны, ноутбуки, аксессуары и многое другое. Быстрая доставка.',
                'meta_keywords' => 'техника, гаджеты, электроника, смартфоны, ноутбуки, доставка',
                'og_title' => "{\$this->name} - Магазин техники и гаджетов",
                'og_description' => 'Продажа качественной электроники и современных гаджетов',
                'canonical_url' => url('/'),
                'robots_meta' => 'index,follow',
                'is_active' => true
            ],
            [
                'page_type' => 'products',
                'page_identifier' => null,
                'meta_title' => "Каталог техники и гаджетов - {$this->name}",
                'meta_description' => 'Полный каталог электроники и гаджетов. Смартфоны, планшеты, ноутбуки, аксессуары и комплектующие.',
                'meta_keywords' => 'каталог электроники, смартфоны, ноутбуки, гаджеты, аксессуары',
                'og_title' => 'Каталог техники и гаджетов',
                'og_description' => 'Широкий выбор современной электроники и гаджетов',
                'canonical_url' => route('shop.products'),
                'robots_meta' => 'index,follow',
                'is_active' => true
            ],
            [
                'page_type' => 'about',
                'page_identifier' => null,
                'meta_title' => "О компании {$this->name} - Магазин техники и гаджетов",
                'meta_description' => "{$this->name} - надежный интернет-магазин электроники и гаджетов. Многолетний опыт и качественный сервис.",
                'meta_keywords' => "о компании {$this->name}, магазин электроники, техника, гаджеты",
                'og_title' => "О компании {$this->name} - магазин техники",
                'og_description' => 'Надежный магазин современной электроники',
                'canonical_url' => route('shop.about'),
                'robots_meta' => 'index,follow',
                'is_active' => true
            ],
            [
                'page_type' => 'cart',
                'page_identifier' => null,
                'meta_title' => "Корзина - {$this->name}",
                'meta_description' => 'Ваша корзина с выбранными товарами. Оформите заказ техники и гаджетов.',
                'meta_keywords' => 'корзина, оформление заказа, покупка техники',
                'og_title' => 'Корзина заказа',
                'og_description' => 'Завершите оформление заказа',
                'canonical_url' => route('shop.cart'),
                'robots_meta' => 'noindex,follow',
                'is_active' => true
            ],
            [
                'page_type' => 'wishlist',
                'page_identifier' => null,
                'meta_title' => "Избранное - {$this->name}",
                'meta_description' => 'Ваш список избранных товаров. Сохраните интересующие гаджеты и технику для последующей покупки.',
                'meta_keywords' => 'избранное, wishlist, отложенные товары',
                'og_title' => 'Избранные товары',
                'og_description' => 'Сохраненные товары для последующего заказа',
                'canonical_url' => route('shop.wishlist'),
                'robots_meta' => 'noindex,follow',
                'is_active' => true
            ],
            [
                'page_type' => 'global',
                'page_identifier' => null,
                'meta_title' => "{$this->name} - Магазин техники и гаджетов",
                'meta_description' => 'Интернет-магазин современной электроники и гаджетов. Широкий ассортимент техники по выгодным ценам.',
                'meta_keywords' => 'техника, гаджеты, электроника, интернет-магазин',
                'og_title' => "{$this->name} - Магазин техники и гаджетов",
                'og_description' => 'Современная электроника и гаджеты по выгодным ценам',
                'canonical_url' => url('/'),
                'robots_meta' => 'index,follow',
                'is_active' => true
            ]
        ];

        $createdCount = 0;

        foreach ($defaultSeoSettings as $seoData) {
            try {
                // Add schema markup based on page type
                $seoData['schema_markup'] = $this->generateSchemaMarkup($seoData['page_type'], $seoData);

                SeoSetting::create($seoData);
                $createdCount++;
                $this->command->info("Created SEO setting for: {$seoData['page_type']}");

            } catch (\Exception $e) {
                $this->command->error("Failed to create SEO setting for {$seoData['page_type']}: " . $e->getMessage());
            }
        }

        $this->command->info("SEO settings seeding completed!");
        $this->command->info("Created: {$createdCount} SEO settings");
    }

    /**
     * Generate schema markup for different page types
     */
    private function generateSchemaMarkup($pageType, $seoData)
    {
        switch ($pageType) {
            case 'home':
            case 'global':
                return [
                    '@context' => 'https://schema.org',
                    '@type' => 'Organization',
                    'name' => "{$this->name}",
                    'url' => url('/'),
                    'description' => $seoData['meta_description'],
                    'address' => [
                        '@type' => 'PostalAddress',
                        'addressLocality' => 'Тестовый город',
                        'addressRegion' => 'Тестовый регион',
                        'addressCountry' => 'XX'
                    ],
                    'contactPoint' => [
                        '@type' => 'ContactPoint',
                        'telephone' => '+1234567890',
                        'contactType' => 'customer service',
                        'availableLanguage' => ['ru', 'en']
                    ],
                    'industry' => 'Электроника и гаджеты',
                    'serviceArea' => [
                        '@type' => 'Country',
                        'name' => 'Демо-страна'
                    ],
                    'hasOfferCatalog' => [
                        '@type' => 'OfferCatalog',
                        'name' => 'Техника и гаджеты',
                        'itemListElement' => [
                            [
                                '@type' => 'OfferCategory',
                                'name' => 'Смартфоны и планшеты'
                            ],
                            [
                                '@type' => 'OfferCategory',
                                'name' => 'Ноутбуки и компьютеры'
                            ],
                            [
                                '@type' => 'OfferCategory',
                                'name' => 'Аксессуары и комплектующие'
                            ]
                        ]
                    ]
                ];

            case 'products':
                return [
                    '@context' => 'https://schema.org',
                    '@type' => 'CollectionPage',
                    'name' => $seoData['meta_title'],
                    'description' => $seoData['meta_description'],
                    'url' => route('shop.products'),
                    'about' => [
                        '@type' => 'Thing',
                        'name' => 'Электроника и гаджеты'
                    ],
                    'audience' => [
                        '@type' => 'Audience',
                        'name' => 'Покупатели электроники и техники'
                    ]
                ];

            case 'about':
                return [
                    '@context' => 'https://schema.org',
                    '@type' => 'AboutPage',
                    'name' => $seoData['meta_title'],
                    'description' => $seoData['meta_description'],
                    'url' => route('shop.about'),
                    'mainEntity' => [
                        '@type' => 'Organization',
                        'name' => "{$this->name}",
                        'description' => 'Интернет-магазин электроники и гаджетов',
                        'foundingLocation' => [
                            '@type' => 'Place',
                            'name' => 'Тестовый город, Демо-страна'
                        ]
                    ]
                ];

            default:
                return [
                    '@context' => 'https://schema.org',
                    '@type' => 'WebPage',
                    'name' => $seoData['meta_title'],
                    'description' => $seoData['meta_description'],
                    'about' => [
                        '@type' => 'Thing',
                        'name' => 'Электроника и гаджеты'
                    ]
                ];
        }
    }
}
