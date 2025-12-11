<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\CarouselSlide;
use App\Models\Category;
use App\Helpers\SettingsHelper;
use App\Helpers\SeoHelper;
use App\Helpers\AnalyticsHelper;
use App\Models\ProductView;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    public function index()
    {
        // Set SEO for home page
        SeoHelper::setSeo('home');

        $carouselSlides = CarouselSlide::active()->ordered()->get();
        $categories = Category::orderBy('name')->get();

        // Get trending products (top 4 most viewed products)
        $trendingProducts = Product::with(['category', 'brand', 'primaryImage'])
            ->leftJoin('product_views', 'products.id', '=', 'product_views.product_id')
            ->select('products.*', \DB::raw('COUNT(product_views.id) as views_count'))
            ->groupBy('products.id')
            ->orderByDesc('views_count')
            ->limit(4)
            ->get();

        // Get site settings
        $siteContact = SettingsHelper::contact();
        $siteSocial = SettingsHelper::social();
        $siteSettings = [
            'phone' => $siteContact['phone'],
            'email' => $siteContact['email'],
            'address' => $siteContact['address'],
            'instagram' => $siteSocial['instagram'],
            'facebook' => $siteSocial['facebook'],
            'twitter' => $siteSocial['twitter'],
            'youtube' => $siteSocial['youtube'],
        ];

        return view('clients.shop.index', compact('carouselSlides', 'categories', 'trendingProducts', 'siteSettings'));
    }

    public function productDetails(Request $request, $slug)
    {
        $product = Product::where('slug', $slug)
            ->with(['category', 'brand', 'images'])
            ->firstOrFail();

        // Set dynamic SEO for product
        $seoData = SeoHelper::generateProductSeo($product);
        SeoHelper::setSeo('product_detail', $product->slug, $seoData);

        // Track product view
        try {
            $visitorIdentifier = AnalyticsHelper::getVisitorIdentifier();
            $userId = Auth::check() ? Auth::id() : null;
            ProductView::recordView($product->id, $visitorIdentifier, $userId, $request->ip());
        } catch (\Exception $e) {
            \Log::error('Failed to track product view: ' . $e->getMessage());
        }

        return view('clients.shop.product-details', compact('product'));
    }

    public function wishlist(Request $request)
    {
        // Get wishlist product IDs from request parameter (passed from JavaScript)
        $wishlistIds = $request->input('ids', '');
        $productIds = [];

        if (!empty($wishlistIds)) {
            // Parse comma-separated IDs
            $productIds = array_filter(explode(',', $wishlistIds), 'is_numeric');
            $productIds = array_map('intval', $productIds);
        }

        // Get products from database
        $wishlistProducts = collect();
        if (!empty($productIds)) {
            $wishlistProducts = Product::with(['category', 'brand', 'primaryImage'])
                ->whereIn('id', $productIds)
                ->get();
        }

        return view('clients.shop.wishlist', compact('wishlistProducts'));
    }

    public function cart()
    {
        // Always return empty collection - products will be loaded via JavaScript
        $cartProducts = collect();
        return view('clients.shop.cart', compact('cartProducts'));
    }

    public function cartData(Request $request)
    {
        // Get cart product IDs from request parameter
        $cartIds = $request->input('ids', '');
        $productIds = [];

        if (!empty($cartIds)) {
            // Parse comma-separated IDs
            $productIds = array_filter(explode(',', $cartIds), 'is_numeric');
            $productIds = array_map('intval', $productIds);
        }

        // Get products from database
        $cartProducts = collect();
        if (!empty($productIds)) {
            $cartProducts = Product::with(['category', 'brand', 'primaryImage'])
                ->whereIn('id', $productIds)
                ->get()
                ->map(function ($product) {
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'slug' => $product->slug,
                        'price' => $product->price,
                        'primary_image' => $product->primaryImage && $product->primaryImage->image_path
                            ? \Storage::url($product->primaryImage->image_path)
                            : asset('build/images/default-image.png'),
                        'category' => $product->category ? $product->category->name : 'Uncategorized',
                    ];
                });
        }

        return response()->json(['products' => $cartProducts]);
    }

    public function products(Request $request, $categorySlug = null)
    {
        // Set SEO for products page
        if ($categorySlug) {
            $selectedCategory = Category::where('slug', $categorySlug)->firstOrFail();
            $seoData = SeoHelper::generateCategorySeo($selectedCategory);
            SeoHelper::setSeo('category', $categorySlug, $seoData);
        } else {
            SeoHelper::setSeo('products');
        }

        // Get all categories for sidebar - load 3 levels
        $categories = Category::whereNull('parent_id')
            ->with(['children.children'])
            ->orderBy('name')
            ->get();

        // Initialize query
        $query = Product::with(['category', 'brand', 'primaryImage']);

        // Handle search
        $search = $request->get('search');
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhereHas('category', function($categoryQuery) use ($search) {
                      $categoryQuery->where('name', 'LIKE', "%{$search}%");
                  })
                  ->orWhereHas('brand', function($brandQuery) use ($search) {
                      $brandQuery->where('name', 'LIKE', "%{$search}%");
                  });
            });
        }

        // Filter by category if specified
        $selectedCategory = null;
        if ($categorySlug) {
            $selectedCategory = Category::where('slug', $categorySlug)
                ->with('children.children')
                ->firstOrFail();

            // Get all category IDs including all descendants recursively
            $categoryIds = $selectedCategory->getAllDescendantIds();

            $query->whereIn('category_id', $categoryIds);
        }

        // Handle view type
        $view = $request->get('view', 'grid');

        // Handle sorting
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'name_az':
                $query->orderBy('name', 'asc');
                break;
            case 'name_za':
                $query->orderBy('name', 'desc');
                break;
            case 'latest':
            default:
                $query->latest();
                break;
        }

        // Paginate results
        $products = $query->paginate(12)->appends(request()->query());
        $allProductsCount = Product::query()->count();

        return view('clients.shop.products', compact('categories', 'products', 'selectedCategory', 'sort', 'view', 'allProductsCount', 'search'));
    }

    public function about()
    {
        // Set SEO for about page
        SeoHelper::setSeo('about');

        $about = \App\Models\About::getActive();

        if (!$about) {
            // Fallback content if no active About content exists
            $about = (object) [
                'title' => 'О нас',
                'content' => '<p>Добро пожаловать в нашу компанию. Мы стремимся предоставлять превосходные продукты и услуги.</p>'
            ];
        }

        // Get map settings
        $mapSettings = \App\Models\MapSetting::getActive();

        return view('clients.shop.about', compact('about', 'mapSettings'));
    }

    public function searchSuggestions(Request $request)
    {
        $query = $request->get('q');

        if (!$query || strlen($query) < 2) {
            return response()->json(['products' => []]);
        }

        $products = Product::with(['category', 'brand', 'primaryImage'])
            ->where(function($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhereHas('category', function($categoryQuery) use ($query) {
                      $categoryQuery->where('name', 'LIKE', "%{$query}%");
                  })
                  ->orWhereHas('brand', function($brandQuery) use ($query) {
                      $brandQuery->where('name', 'LIKE', "%{$query}%");
                  });
            })
            ->limit(8)
            ->get()
            ->map(function($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'price' => $product->price,
                    'formatted_price' => number_format($product->price, 2) . ' СОМ',
                    'category' => $product->category ? $product->category->name : 'Без категории',
                    'brand' => $product->brand ? $product->brand->name : null,
                    'image' => $product->primaryImage && $product->primaryImage->image_path
                        ? \Storage::url($product->primaryImage->image_path)
                        : asset('build/images/default-image.png'),
                    'url' => route('shop.product-details', $product->slug)
                ];
            });

        return response()->json([
            'products' => $products,
            'total' => $products->count(),
            'search_url' => route('shop.products', ['search' => $query])
        ]);
    }
}
