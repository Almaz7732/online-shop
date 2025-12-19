<?php

namespace App\Helpers;

use App\Models\ProductView;
use App\Models\SiteVisit;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsHelper
{
    /**
     * Cache duration in minutes
     */
    const CACHE_DURATION = 5;

    /**
     * Get or create visitor identifier from cookie
     */
    public static function getVisitorIdentifier()
    {
        $cookieName = 'visitor_id';

        if (isset($_COOKIE[$cookieName])) {
            return $_COOKIE[$cookieName];
        }

        // Generate unique identifier
        $identifier = md5(uniqid(rand(), true));

        // Set cookie for 1 year
        setcookie($cookieName, $identifier, time() + (365 * 24 * 60 * 60), '/');

        return $identifier;
    }

    /**
     * Get top viewed products
     */
    public static function getTopProducts($startDate = null, $endDate = null, $limit = 10)
    {
        $cacheKey = "analytics_top_products_{$startDate}_{$endDate}_{$limit}";

        return Cache::remember($cacheKey, self::CACHE_DURATION, function() use ($startDate, $endDate, $limit) {
            $query = ProductView::select('product_id', DB::raw('COUNT(*) as views_count'))
                ->groupBy('product_id')
                ->with(['product.primaryImage', 'product.category', 'product.brand']);

            if ($startDate) {
                $query->where('viewed_at', '>=', Carbon::parse($startDate));
            }

            if ($endDate) {
                $query->where('viewed_at', '<=', Carbon::parse($endDate)->endOfDay());
            }

            return $query->orderBy('views_count', 'desc')
                ->limit($limit)
                ->get()
                ->map(function($view) {
                    return [
                        'product' => $view->product,
                        'views_count' => $view->views_count,
                    ];
                });
        });
    }

    /**
     * Get total site visits count
     */
    public static function getSiteVisitsCount($startDate = null, $endDate = null)
    {
        $cacheKey = "analytics_site_visits_count_{$startDate}_{$endDate}";

        return Cache::remember($cacheKey, self::CACHE_DURATION, function() use ($startDate, $endDate) {
            $query = SiteVisit::query();

            if ($startDate) {
                $query->where('visited_at', '>=', Carbon::parse($startDate));
            }

            if ($endDate) {
                $query->where('visited_at', '<=', Carbon::parse($endDate)->endOfDay());
            }

            return $query->count();
        });
    }

    /**
     * Get unique visitors count
     */
    public static function getUniqueVisitorsCount($startDate = null, $endDate = null)
    {
        $cacheKey = "analytics_unique_visitors_{$startDate}_{$endDate}";

        return Cache::remember($cacheKey, self::CACHE_DURATION, function() use ($startDate, $endDate) {
            $query = SiteVisit::select('visitor_identifier');

            if ($startDate) {
                $query->where('visited_at', '>=', Carbon::parse($startDate));
            }

            if ($endDate) {
                $query->where('visited_at', '<=', Carbon::parse($endDate)->endOfDay());
            }

            return $query->distinct()->count('visitor_identifier');
        });
    }

    /**
     * Get total product views count
     */
    public static function getProductViewsCount($startDate = null, $endDate = null)
    {
        $cacheKey = "analytics_product_views_count_{$startDate}_{$endDate}";

        return Cache::remember($cacheKey, self::CACHE_DURATION, function() use ($startDate, $endDate) {
            $query = ProductView::query();

            if ($startDate) {
                $query->where('viewed_at', '>=', Carbon::parse($startDate));
            }

            if ($endDate) {
                $query->where('viewed_at', '<=', Carbon::parse($endDate)->endOfDay());
            }

            return $query->count();
        });
    }

    /**
     * Get site visits data for chart (grouped by day)
     */
    public static function getSiteVisitsChartData($startDate = null, $endDate = null)
    {
        $cacheKey = "analytics_site_visits_chart_{$startDate}_{$endDate}";

        return Cache::remember($cacheKey, self::CACHE_DURATION, function() use ($startDate, $endDate) {
            $start = $startDate ? Carbon::parse($startDate) : Carbon::now()->subDays(30);
            $end = $endDate ? Carbon::parse($endDate) : Carbon::now();

            $visits = SiteVisit::select(
                    DB::raw('DATE(visited_at) as date'),
                    DB::raw('COUNT(*) as count'),
                    DB::raw('COUNT(DISTINCT visitor_identifier) as unique_count')
                )
                ->whereBetween('visited_at', [$start, $end->endOfDay()])
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            $dates = [];
            $counts = [];
            $uniqueCounts = [];

            foreach ($visits as $visit) {
                $dates[] = Carbon::parse($visit->date)->format('d M');
                $counts[] = $visit->count;
                $uniqueCounts[] = $visit->unique_count;
            }

            return [
                'dates' => $dates,
                'total_visits' => $counts,
                'unique_visitors' => $uniqueCounts,
            ];
        });
    }

    /**
     * Get product views data for chart (top products)
     */
    public static function getProductViewsChartData($startDate = null, $endDate = null, $limit = 10)
    {
        $cacheKey = "analytics_product_views_chart_{$startDate}_{$endDate}_{$limit}";

        return Cache::remember($cacheKey, self::CACHE_DURATION, function() use ($startDate, $endDate, $limit) {
            $topProducts = self::getTopProducts($startDate, $endDate, $limit);

            $productNames = [];
            $viewCounts = [];

            foreach ($topProducts as $item) {
                $productNames[] = $item['product']->name;
                $viewCounts[] = $item['views_count'];
            }

            return [
                'product_names' => $productNames,
                'view_counts' => $viewCounts,
            ];
        });
    }

    /**
     * Get analytics summary
     */
    public static function getSummary($startDate = null, $endDate = null)
    {
        return [
            'total_visits' => self::getSiteVisitsCount($startDate, $endDate),
            'unique_visitors' => self::getUniqueVisitorsCount($startDate, $endDate),
            'product_views' => self::getProductViewsCount($startDate, $endDate),
            'top_products' => self::getTopProducts($startDate, $endDate, 10),
        ];
    }

    /**
     * Clear analytics cache
     */
    public static function clearCache()
    {
        Cache::flush();
        \Log::info('Analytics cache cleared');
    }

    /**
     * Get average views per product
     */
    public static function getAverageViewsPerProduct($startDate = null, $endDate = null)
    {
        $totalViews = self::getProductViewsCount($startDate, $endDate);
        $uniqueProducts = ProductView::query();

        if ($startDate) {
            $uniqueProducts->where('viewed_at', '>=', Carbon::parse($startDate));
        }

        if ($endDate) {
            $uniqueProducts->where('viewed_at', '<=', Carbon::parse($endDate)->endOfDay());
        }

        $productCount = $uniqueProducts->distinct()->count('product_id');

        return $productCount > 0 ? round($totalViews / $productCount, 2) : 0;
    }
}
