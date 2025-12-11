<?php

namespace App\Helpers;

use App\Models\SeoSetting;
use Illuminate\Support\Facades\View;

class SeoHelper
{
    /**
     * Set SEO data for current page
     */
    public static function setSeo($pageType, $identifier = null, $data = [])
    {
        // Get SEO settings from database
        $seoSetting = SeoSetting::getForPage($pageType, $identifier);

        // If no specific SEO found, try global settings
        if (!$seoSetting) {
            $seoSetting = SeoSetting::getGlobalSeo();
        }

        // Merge with provided data (data from controller overrides database)
        $seoData = self::mergeSeoData($seoSetting, $data);

        // Share SEO data with all views
        View::share('seoData', $seoData);

        return $seoData;
    }

    /**
     * Merge SEO settings with provided data
     */
    private static function mergeSeoData($seoSetting, $data)
    {
        $defaultData = [
            'meta_title' =>  config('app.name', 'Example') . ' - Магазин техники и гаджетов',
            'meta_description' => 'Интернет-магазин электроники и гаджетов. Смартфоны, ноутбуки, аксессуары и многое другое. Быстрая доставка.',
            'meta_keywords' => 'техника, гаджеты, электроника, смартфоны, ноутбуки, доставка',
            'og_title' => '',
            'og_description' => '',
            'og_image' => '',
            'canonical_url' => url()->current(),
            'robots_meta' => 'index,follow',
            'schema_markup' => []
        ];

        // Start with defaults
        $mergedData = $defaultData;

        // Override with database settings if available
        if ($seoSetting) {
            $mergedData = array_merge($mergedData, [
                'meta_title' => $seoSetting->getMetaTitleWithFallback($defaultData['meta_title']),
                'meta_description' => $seoSetting->getMetaDescriptionWithFallback($defaultData['meta_description']),
                'meta_keywords' => $seoSetting->meta_keywords ?: $defaultData['meta_keywords'],
                'og_title' => $seoSetting->og_title ?: $seoSetting->getMetaTitleWithFallback($defaultData['meta_title']),
                'og_description' => $seoSetting->og_description ?: $seoSetting->getMetaDescriptionWithFallback($defaultData['meta_description']),
                'og_image' => $seoSetting->og_image ?: $defaultData['og_image'],
                'canonical_url' => $seoSetting->canonical_url ?: $defaultData['canonical_url'],
                'robots_meta' => $seoSetting->robots_meta ?: $defaultData['robots_meta'],
                'schema_markup' => $seoSetting->schema_markup ?: $defaultData['schema_markup']
            ]);
        }

        // Finally override with controller data
        $mergedData = array_merge($mergedData, array_filter($data));

        return $mergedData;
    }

    /**
     * Generate dynamic SEO for product
     */
    public static function generateProductSeo($product)
    {
        return [
            'meta_title' => "{$product->name} - Магазин техники и гаджетов | " . config('app.name', 'Example'),
            'meta_description' => "Купить {$product->name} в Бишкеке. " .
                                ($product->description ? substr(strip_tags($product->description), 0, 120) . '...' : 'Магазин техники и гаджетов.'),
            'meta_keywords' => "{$product->name}, техники и гаджеты, купить, Бишкек, Кыргызстан",
            'og_title' => $product->name,
            'og_description' => $product->description ? substr(strip_tags($product->description), 0, 150) . '...' : 'Магазин техники и гаджетов',
            'og_image' => $product->primaryImage && $product->primaryImage->image_path
                        ? asset('storage/' . $product->primaryImage->image_path)
                        : asset('build/images/default-image.png'),
            'canonical_url' => route('shop.product-details', $product->slug),
            'schema_markup' => [
                '@context' => 'https://schema.org',
                '@type' => 'Product',
                'name' => $product->name,
                'description' => strip_tags($product->description ?: 'Техники и гаджеты'),
                'image' => $product->primaryImage && $product->primaryImage->image_path
                         ? asset('storage/' . $product->primaryImage->image_path)
                         : asset('build/images/default-image.png'),
                'offers' => [
                    '@type' => 'Offer',
                    'price' => $product->price,
                    'priceCurrency' => 'KGS',
                    'availability' => 'https://schema.org/InStock',
                    'seller' => [
                        '@type' => 'Organization',
                        'name' => config('app.name', 'Example')
                    ]
                ],
                'brand' => [
                    '@type' => 'Brand',
                    'name' => $product->brand ? $product->brand->name : config('app.name', 'Example')
                ],
                'category' => $product->category ? $product->category->name : 'Техники и гаджеты'
            ]
        ];
    }

    /**
     * Generate dynamic SEO for category
     */
    public static function generateCategorySeo($category)
    {
        return [
            'meta_title' => "{$category->name} - Магазин техники и гаджетов | " . config('app.name', 'Example'),
            'meta_description' => "Широкий выбор товаров в категории {$category->name}. Качественные техники и гаджеты с доставкой по Бишкеку.",
            'meta_keywords' => "{$category->name}, стоматологическое оборудование, Бишкек, купить, доставка",
            'og_title' => $category->name,
            'og_description' => "Качественные техники и гаджеты в категории {$category->name}",
            'canonical_url' => route('shop.products.category', $category->slug),
            'schema_markup' => [
                '@context' => 'https://schema.org',
                '@type' => 'CollectionPage',
                'name' => $category->name,
                'description' => "Техники и гаджеты в категории {$category->name}",
                'url' => route('shop.products.category', $category->slug)
            ]
        ];
    }

    /**
     * Render meta tags HTML
     */
    public static function renderMetaTags($seoData = null)
    {
        if (!$seoData) {
            $seoData = View::shared('seoData', []);
        }

        $html = '';

        // Basic meta tags
        if (!empty($seoData['meta_title'])) {
            $html .= '<title>' . e($seoData['meta_title']) . '</title>' . "\n";
        }

        if (!empty($seoData['meta_description'])) {
            $html .= '<meta name="description" content="' . e($seoData['meta_description']) . '">' . "\n";
        }

        if (!empty($seoData['meta_keywords'])) {
            $html .= '<meta name="keywords" content="' . e($seoData['meta_keywords']) . '">' . "\n";
        }

        if (!empty($seoData['robots_meta'])) {
            $html .= '<meta name="robots" content="' . e($seoData['robots_meta']) . '">' . "\n";
        }

        if (!empty($seoData['canonical_url'])) {
            $html .= '<link rel="canonical" href="' . e($seoData['canonical_url']) . '">' . "\n";
        }

        // Open Graph tags
        if (!empty($seoData['og_title'])) {
            $html .= '<meta property="og:title" content="' . e($seoData['og_title']) . '">' . "\n";
        }

        if (!empty($seoData['og_description'])) {
            $html .= '<meta property="og:description" content="' . e($seoData['og_description']) . '">' . "\n";
        }

        if (!empty($seoData['og_image'])) {
            $html .= '<meta property="og:image" content="' . e($seoData['og_image']) . '">' . "\n";
        }

        $html .= '<meta property="og:type" content="website">' . "\n";
        $html .= '<meta property="og:url" content="' . e(url()->current()) . '">' . "\n";

        // Schema.org JSON-LD
        if (!empty($seoData['schema_markup']) && is_array($seoData['schema_markup'])) {
            $html .= '<script type="application/ld+json">' . "\n";
            $html .= json_encode($seoData['schema_markup'], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . "\n";
            $html .= '</script>' . "\n";
        }

        return $html;
    }
}
