<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class StoreController extends Controller
{
    /**
     * Display the public store with product listings.
     */
    public function index()
    {
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

        $products = $query->paginate(12)->appends(request()->query());
        $categories = Category::orderBy('name')->get();

        return view('store.index', compact('products', 'categories'));
    }
}
