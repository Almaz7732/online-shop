# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a Laravel 11 application built as an admin panel with a client-facing shop interface. The project uses the Skote admin template (v4.2.1) and includes both backend PHP/Laravel functionality and frontend assets managed by Vite.

## Architecture

- **Backend**: Laravel 11 framework with PHP 8.2+
- **Frontend**: Vite-based asset compilation with SCSS and JavaScript
- **Database**: MySQL (configured) with SQLite fallback for development
- **Authentication**: Laravel UI with built-in auth system
- **Permissions**: Spatie Laravel Permission package for role-based access control
- **Admin Panel**: Located under `/admin` route, uses Skote template
- **Client Interface**: Shop functionality accessible from root `/` route

### Key Directories

- `app/Http/Controllers/` - Main application controllers
  - `HomeController.php` - Admin panel and template routing
  - `ShopController.php` - Client-facing shop functionality
  - `CustomerController.php` - Customer management functionality
  - `Auth/` - Authentication controllers
- `resources/views/` - Blade templates
  - `admin/` - Admin panel views
  - `clients/` - Client-facing shop views
  - `templates/` - Dynamic template system
- `resources/clients/` - Client-specific assets (CSS, JS, images, fonts)
- `database/migrations/` - Database schema files
- `app/Models/` - Eloquent models (User, Product, Category, Brand, Order, CarouselSlide, About, MainSetting, SeoSetting, MapSetting)
- `app/Helpers/` - Helper classes
  - `SettingsHelper.php` - Cached settings management
  - `SeoHelper.php` - Dynamic SEO generation and meta tag management

### Routing Architecture

The application uses a dual-interface approach:
1. **Client Interface**: Public shop routes handled by `ShopController`
   - `/` - Home page with carousel and trending products
   - `/products` - Product listing with filters, search, and sorting
   - `/products/category/{slug}` - Category-filtered products
   - `/product/{slug}` - Product details page
   - `/cart`, `/wishlist` - Shopping cart and wishlist pages
   - `/about` - About page with map integration
   - `/search-suggestions` - AJAX endpoint for search autocomplete
2. **Admin Interface**: Protected routes (auth + `admin-setting` permission) under `/admin` prefix
   - Resource controllers for brands, categories, products, SEO settings, and about pages
   - Product image management endpoints
   - Site settings and carousel management
   - Order management with DataTables integration
   - Map settings configuration
3. **Authentication**: Laravel UI routes with registration/reset disabled
4. **Language Support**: Route `/index/{locale}` for switching between 5 supported languages

## Development Commands

### Environment Setup
```bash
# Install PHP dependencies
composer install

# Install Node dependencies
npm install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### Development Workflow
```bash
# Start Laravel development server
php artisan serve

# Start Vite development server (for asset compilation)
npm run dev

# Build assets for production
npm run build

# Build RTL CSS assets
npm run build-rtl
```

### Testing
```bash
# Run all tests
php artisan test
# or
./vendor/bin/phpunit

# Run specific test suite
./vendor/bin/phpunit --testsuite=Unit
./vendor/bin/phpunit --testsuite=Feature

# Run single test file
./vendor/bin/phpunit tests/Feature/ExampleTest.php

# Run single test method
./vendor/bin/phpunit --filter=test_example
```

### Code Quality
```bash
# Format PHP code with Laravel Pint
./vendor/bin/pint

# StyleCI integration available for automated code style checking

# Clear application caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### Database Operations
```bash
# Run migrations
php artisan migrate

# Seed database
php artisan db:seed

# Fresh migration with seeding
php artisan migrate:fresh --seed

# Create storage symbolic link (required for product images)
php artisan storage:link
```

### Maintenance Commands
```bash
# Generate sitemap
php artisan sitemap:generate

# Clean up old analytics data (default: keep 1 year)
php artisan analytics:cleanup
php artisan analytics:cleanup --days=180  # Keep 6 months

# Clear permissions cache (after role/permission changes)
php artisan permission:cache-reset

# Optimize for production
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Asset Management

The project uses Vite for asset compilation with custom configuration defined in `vite.config.js`:
- **Input files**:
  - `resources/scss/bootstrap.scss` - Bootstrap styles
  - `resources/scss/icons.scss` - Icon styles
  - `resources/scss/app.scss` - Main application styles
  - `resources/clients/css/app.css` - Client-specific styles
  - `resources/clients/js/app.js` - Client-specific JavaScript
- **Output directory**: `public/build/`
- **Static asset copying**: Fonts, images, JS libraries, JSON files, and client assets are copied from `resources/` to `public/build/`
- **RTL Support**: Available via `npm run build-rtl` command using rtlcss

## Key Features

- **Multi-language Support**: 5 supported languages (English, Greek, Italian, Russian, Spanish) with switching via `/index/{locale}` route
- **DataTables Integration**: Uses `yajra/laravel-datatables` package for admin tables
- **Dual Interface**: Separate admin and client interfaces
- **Template System**: Dynamic view resolution in `templates/` directory
- **Authentication**: Built-in Laravel auth with profile/password update functionality. Registration, reset, verify, and confirm disabled by default
- **Permission System**: Role-based access control using Spatie Laravel Permission package. Admin routes require `admin-setting` permission
- **SEO Management**: Dynamic SEO with SeoHelper for products, categories, and pages. Includes meta tags, Open Graph, and Schema.org JSON-LD
- **E-commerce Features**: Product catalog with categories, brands, images, cart/wishlist (localStorage-based), search with suggestions, and order management
- **Settings Management**: Centralized settings via SettingsHelper with 60-minute cache, including site contact, social media, carousel slides, and theme configuration
- **Telegram Integration**: Uses `irazasyed/telegram-bot-sdk` package

## Helper Functions

The application includes global helper functions in `app/helpers.php`:
- `setting($key, $default)` - Access application settings via SettingsHelper
- `carousel_slides()` - Get active carousel slides
- `site_contact()` - Get contact information
- `site_social()` - Get social media links
- `site_theme()` - Get theme configuration

## Data Management Patterns

### Settings System
- **SettingsHelper**: Centralized settings management with 60-minute caching
  - Use `setting($key, $default)` helper function to access settings
  - Cache is automatically cleared when settings are updated
  - Available methods: `get()`, `set()`, `all()`, `contact()`, `social()`, `carousel()`, `theme()`, `clearCache()`

### SEO System
- **SeoHelper**: Dynamic SEO generation for pages, products, and categories
  - Call `SeoHelper::setSeo($pageType, $identifier, $data)` in controllers
  - Automatically generates meta tags, Open Graph tags, and Schema.org JSON-LD
  - Supports page-specific overrides and global fallbacks
  - Available for: home, products, product_detail, category, about pages

### Analytics System
- **AnalyticsHelper**: Visitor and product view tracking with caching
  - **Visitor Identification**: Uses cookie-based identifier (1-year expiration) for tracking unique visitors
  - **Product Views**: Tracks unique product views per visitor per day (deduplication)
  - **Site Visits**: Records site visits with 30-minute throttling per visitor
  - **Models**:
    - `ProductView` - tracks product page visits (product_id, visitor_identifier, user_id, ip_address, viewed_at)
    - `SiteVisit` - tracks general site visits (visitor_identifier, user_id, page_url, referer, ip_address, user_agent, visited_at)
  - **TrackSiteVisit Middleware**: Automatically tracks visits for all non-admin, non-API routes in 'web' middleware group
  - **Available Methods**:
    - `AnalyticsHelper::getVisitorIdentifier()` - Get or create visitor cookie
    - `AnalyticsHelper::getTopProducts($startDate, $endDate, $limit)` - Get most viewed products
    - `AnalyticsHelper::getSiteVisitsCount($startDate, $endDate)` - Total site visits
    - `AnalyticsHelper::getUniqueVisitorsCount($startDate, $endDate)` - Unique visitors
    - `AnalyticsHelper::getProductViewsCount($startDate, $endDate)` - Total product views
    - `AnalyticsHelper::getSiteVisitsChartData($startDate, $endDate)` - Data for charts
    - `AnalyticsHelper::getProductViewsChartData($startDate, $endDate, $limit)` - Product views chart data
  - **Dashboard**: `/admin` displays analytics widgets, charts (ApexCharts), and top products table
    - Dashboard loads statistics via AJAX: `GET /admin/statistics?start_date=YYYY-MM-DD&end_date=YYYY-MM-DD`
    - Returns: orders count, revenue, growth percentage, site visits, unique visitors, product views, and chart data
  - **Cleanup Command**: `php artisan analytics:cleanup --days=365` - Remove old analytics data (default: keep 1 year)
  - **Caching**: All analytics queries cached for 5 minutes (const CACHE_DURATION = 5)

### Product Management
- Products have relationships with Category, Brand, ProductImage, and ProductView models
- Primary image system: one image marked as primary per product
- Images stored using Laravel Storage in `storage/app/public/`
- Product slugs auto-generated for SEO-friendly URLs
- Product views automatically tracked when product details page is visited

### Cart and Wishlist
- Client-side implementation using localStorage
- Server provides data endpoints: `/cart-data`, `/wishlist` accept comma-separated IDs
- No user authentication required for cart/wishlist functionality

### Sitemap Generation
- **Command**: `php artisan sitemap:generate` - Generates XML sitemap at `public/sitemap.xml`
- **Includes**:
  - Static pages (home, products, about) with priority 0.9
  - All product pages with priority 0.8 and weekly change frequency
  - All category pages with priority 0.7 and weekly change frequency
- Uses `spatie/laravel-sitemap` package for sitemap generation
- Sitemap includes last modification dates from model timestamps

## Environment Configuration

Key environment variables to configure:
- Database settings (`DB_*`)
- Application settings (`APP_NAME`, `APP_URL`, `APP_DEBUG`)
- Mail configuration for notifications
- Cache/Session drivers for performance
- Telegram Bot API key (if using Telegram integration)