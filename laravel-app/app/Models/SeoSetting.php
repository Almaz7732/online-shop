<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SeoSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_type',
        'page_identifier',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_title',
        'og_description',
        'og_image',
        'canonical_url',
        'robots_meta',
        'schema_markup',
        'is_active'
    ];

    protected $casts = [
        'schema_markup' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get SEO settings for a specific page
     */
    public static function getForPage($pageType, $identifier = null)
    {
        $cacheKey = "seo_{$pageType}" . ($identifier ? "_{$identifier}" : '');

        return Cache::remember($cacheKey, 60 * 60, function () use ($pageType, $identifier) {
            return static::where('page_type', $pageType)
                ->where('page_identifier', $identifier)
                ->where('is_active', true)
                ->first();
        });
    }

    /**
     * Set SEO settings for a page
     */
    public static function setForPage($pageType, $data, $identifier = null)
    {
        $seo = static::updateOrCreate(
            [
                'page_type' => $pageType,
                'page_identifier' => $identifier
            ],
            array_merge($data, ['is_active' => true])
        );

        // Clear cache
        $cacheKey = "seo_{$pageType}" . ($identifier ? "_{$identifier}" : '');
        Cache::forget($cacheKey);

        return $seo;
    }

    /**
     * Get global/default SEO settings
     */
    public static function getGlobalSeo()
    {
        return static::getForPage('global');
    }

    /**
     * Generate structured data for different page types
     */
    public function generateSchemaMarkup($pageData = [])
    {
        $schema = [];

        switch ($this->page_type) {
            case 'home':
                $schema = [
                    '@context' => 'https://schema.org',
                    '@type' => 'Organization',
                    'name' => config('app.name', 'Example'),
                    'url' => url('/'),
                    'description' => $this->meta_description,
                ];
                break;

            case 'product_detail':
                if (isset($pageData['product'])) {
                    $product = $pageData['product'];
                    $schema = [
                        '@context' => 'https://schema.org',
                        '@type' => 'Product',
                        'name' => $product->name,
                        'description' => $product->description,
                        'offers' => [
                            '@type' => 'Offer',
                            'price' => $product->price,
                            'priceCurrency' => 'KGS',
                            'availability' => 'https://schema.org/InStock'
                        ]
                    ];
                }
                break;

            case 'products':
                $schema = [
                    '@context' => 'https://schema.org',
                    '@type' => 'CollectionPage',
                    'name' => $this->meta_title,
                    'description' => $this->meta_description,
                ];
                break;
        }

        return $schema;
    }

    /**
     * Get meta title with fallback
     */
    public function getMetaTitleWithFallback($fallback = null)
    {
        return $this->meta_title ?: $fallback ?: config('app.name');
    }

    /**
     * Get meta description with fallback
     */
    public function getMetaDescriptionWithFallback($fallback = null)
    {
        return $this->meta_description ?: $fallback ?: 'Качественные товары';
    }

    /**
     * Scope for active SEO settings
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for specific page type
     */
    public function scopeByPage($query, $pageType, $identifier = null)
    {
        return $query->where('page_type', $pageType)
                    ->where('page_identifier', $identifier);
    }

    /**
     * Boot method for automatic cache clearing
     */
    protected static function boot()
    {
        parent::boot();

        static::saved(function ($model) {
            $model->clearCache();
        });

        static::deleted(function ($model) {
            $model->clearCache();
        });
    }

    /**
     * Clear SEO cache for this model
     */
    public function clearCache()
    {
        $cacheKey = "seo_{$this->page_type}" . ($this->page_identifier ? "_{$this->page_identifier}" : '');
        Cache::forget($cacheKey);
    }

    /**
     * Get available page types
     */
    public static function getPageTypes()
    {
        return [
            'home' => 'Главная страница',
            'products' => 'Каталог товаров',
            'category' => 'Страница категории',
            'product_detail' => 'Страница товара',
            'about' => 'О компании',
            'contact' => 'Контакты',
            'cart' => 'Корзина',
            'wishlist' => 'Избранное',
            'global' => 'Глобальные настройки'
        ];
    }
}
