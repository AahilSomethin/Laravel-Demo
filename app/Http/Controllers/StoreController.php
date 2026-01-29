<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;

class StoreController extends Controller
{
    /**
     * Cache key prefix for store products.
     */
    public const PRODUCTS_CACHE_PREFIX = 'store.products.';

    /**
     * Cache key for categories.
     */
    public const CATEGORIES_CACHE_KEY = 'store.categories';

    /**
     * Display the public store with product listings.
     */
    public function index()
    {
        // Build cache key from query parameters
        $cacheKey = $this->buildProductsCacheKey();

        // Cache products for 60 seconds (short TTL due to potential frequent changes)
        $products = Cache::remember($cacheKey, 60, function () {
            $query = Product::with('category')->where('is_active', true);

            // Search by product name
            if ($search = request('search')) {
                $query->where('name', 'like', '%' . $search . '%');
            }

            // Filter by category
            if ($categoryIds = request('categories')) {
                $query->whereIn('category_id', (array) $categoryIds);
            }

            // Filter by price range
            if ($minPrice = request('min_price')) {
                $query->where('price', '>=', $minPrice);
            }

            if ($maxPrice = request('max_price')) {
                $query->where('price', '<=', $maxPrice);
            }

            // Sort products
            $sort = request('sort', 'newest');
            switch ($sort) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'newest':
                default:
                    $query->latest();
                    break;
            }

            return $query->paginate(12)->appends(request()->query());
        });

        // Cache categories for 1 hour since they rarely change
        $categories = Cache::remember(self::CATEGORIES_CACHE_KEY, 3600, function () {
            return Category::orderBy('name')->get();
        });

        return view('store.index', compact('products', 'categories'));
    }

    /**
     * Build a cache key based on current query parameters.
     */
    protected function buildProductsCacheKey(): string
    {
        $params = [
            'search' => request('search', ''),
            'categories' => request('categories', []),
            'min_price' => request('min_price', ''),
            'max_price' => request('max_price', ''),
            'sort' => request('sort', 'newest'),
            'page' => request('page', 1),
        ];

        // Sort categories array for consistent cache keys
        if (is_array($params['categories'])) {
            sort($params['categories']);
        }

        return self::PRODUCTS_CACHE_PREFIX . md5(json_encode($params));
    }

    /**
     * Clear all product listing caches.
     * Called when products are created, updated, or deleted.
     */
    public static function clearProductsCache(): void
    {
        // For file/database cache drivers, we can't easily clear by prefix
        // So we use cache tags if available, otherwise clear all cache
        if (method_exists(Cache::getStore(), 'tags')) {
            Cache::tags(['store.products'])->flush();
        } else {
            // For simpler cache drivers, we flush the entire cache
            // In production, consider using Redis/Memcached with tags
            Cache::flush();
        }
    }

    /**
     * Clear categories cache.
     */
    public static function clearCategoriesCache(): void
    {
        Cache::forget(self::CATEGORIES_CACHE_KEY);
    }
}
