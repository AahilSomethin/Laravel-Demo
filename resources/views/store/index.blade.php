<x-store-layout>
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-semibold text-white">Store</h1>
        <p class="text-neutral-400 mt-2">Discover our collection of products</p>
    </div>

    <!-- Main Layout: Sidebar + Content -->
    <div class="flex flex-col lg:flex-row gap-8">
        
        <!-- Sidebar Filters -->
        <aside class="w-full lg:w-64 shrink-0">
            <form method="GET" action="{{ route('store.index') }}" class="bg-neutral-900 rounded-xl p-5 lg:sticky lg:top-8">
                <h2 class="text-white font-semibold mb-5">Filters</h2>

                <!-- Category Filter -->
                <div class="mb-6">
                    <h3 class="text-sm text-white font-semibold mb-3">Category</h3>
                    <div class="space-y-2">
                        @foreach($categories as $category)
                            <label class="flex items-center cursor-pointer">
                                <input 
                                    type="checkbox" 
                                    name="categories[]" 
                                    value="{{ $category->id }}"
                                    {{ in_array($category->id, (array) request('categories', [])) ? 'checked' : '' }}
                                    class="w-4 h-4 rounded bg-neutral-800 border-neutral-700 text-white focus:ring-neutral-500 focus:ring-offset-neutral-900">
                                <span class="ml-2 text-sm text-neutral-400">{{ $category->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Price Range Filter -->
                <div class="mb-6">
                    <h3 class="text-sm text-white font-semibold mb-3">Price Range</h3>
                    <div class="flex gap-3">
                        <input 
                            type="number" 
                            name="min_price" 
                            placeholder="Min" 
                            value="{{ request('min_price') }}"
                            step="0.01"
                            min="0"
                            class="w-full px-3 py-2 bg-neutral-800 border border-neutral-700 text-neutral-100 placeholder:text-neutral-500 rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-neutral-500 focus:border-neutral-500 transition"
                        >
                        <input 
                            type="number" 
                            name="max_price" 
                            placeholder="Max" 
                            value="{{ request('max_price') }}"
                            step="0.01"
                            min="0"
                            class="w-full px-3 py-2 bg-neutral-800 border border-neutral-700 text-neutral-100 placeholder:text-neutral-500 rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-neutral-500 focus:border-neutral-500 transition"
                        >
                    </div>
                </div>

                <!-- Preserve search and sort when filtering -->
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif
                @if(request('sort'))
                    <input type="hidden" name="sort" value="{{ request('sort') }}">
                @endif

                <!-- Filter Actions -->
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 px-4 py-2.5 bg-white text-black font-medium text-sm rounded-lg hover:bg-neutral-200 transition">
                        Apply
                    </button>
                    <a href="{{ route('store.index') }}" class="flex-1 px-4 py-2.5 bg-neutral-800 text-white font-medium text-sm rounded-lg hover:bg-neutral-700 transition text-center">
                        Reset
                    </a>
                </div>
            </form>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 min-w-0">
            
            <!-- Top Bar: Search + Sort -->
            <div class="bg-neutral-900 rounded-xl p-4 mb-6">
                <form method="GET" action="{{ route('store.index') }}" class="flex flex-col sm:flex-row gap-4">
                    <!-- Search Input -->
                    <div class="flex-1">
                        <div class="relative">
                            <input 
                                type="text" 
                                name="search" 
                                placeholder="Search products..." 
                                value="{{ request('search') }}"
                                class="w-full pl-10 pr-4 py-2.5 bg-neutral-800 border border-neutral-700 text-neutral-100 placeholder:text-neutral-500 rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-neutral-500 focus:border-neutral-500 transition"
                            >
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Sort Dropdown -->
                    <div class="flex gap-3">
                        <select 
                            name="sort" 
                            onchange="this.form.submit()"
                            class="px-4 py-2.5 bg-neutral-800 border border-neutral-700 text-neutral-100 rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-neutral-500 focus:border-neutral-500 transition appearance-none cursor-pointer pr-10"
                            style="background-image: url('data:image/svg+xml;charset=UTF-8,%3csvg xmlns=%27http://www.w3.org/2000/svg%27 viewBox=%270 0 24 24%27 fill=%27none%27 stroke=%27%23737373%27 stroke-width=%272%27 stroke-linecap=%27round%27 stroke-linejoin=%27round%27%3e%3cpolyline points=%276 9 12 15 18 9%27%3e%3c/polyline%3e%3c/svg%3e'); background-repeat: no-repeat; background-position: right 0.75rem center; background-size: 1rem;"
                        >
                            <option value="newest" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>Newest</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                        </select>

                        <!-- Preserve filters when sorting -->
                        @if(request('min_price'))
                            <input type="hidden" name="min_price" value="{{ request('min_price') }}">
                        @endif
                        @if(request('max_price'))
                            <input type="hidden" name="max_price" value="{{ request('max_price') }}">
                        @endif
                        @if(request('categories'))
                            @foreach((array) request('categories') as $catId)
                                <input type="hidden" name="categories[]" value="{{ $catId }}">
                            @endforeach
                        @endif

                        <button type="submit" class="px-4 py-2.5 bg-neutral-800 text-white font-medium text-sm rounded-lg hover:bg-neutral-700 transition">
                            Search
                        </button>
                    </div>
                </form>
            </div>

            <!-- Active Filters Display -->
            @if(request('search') || request('min_price') || request('max_price') || request('categories'))
                <div class="flex flex-wrap gap-2 mb-6">
                    @if(request('search'))
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-neutral-800 text-neutral-300 border border-neutral-700">
                            Search: "{{ request('search') }}"
                            <a href="{{ route('store.index', array_merge(request()->except('search'), [])) }}" class="ml-2 text-neutral-500 hover:text-white">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </a>
                        </span>
                    @endif
                    @if(request('categories'))
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-neutral-800 text-neutral-300 border border-neutral-700">
                            {{ count((array) request('categories')) }} {{ Str::plural('category', count((array) request('categories'))) }} selected
                            <a href="{{ route('store.index', array_merge(request()->except('categories'), [])) }}" class="ml-2 text-neutral-500 hover:text-white">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </a>
                        </span>
                    @endif
                    @if(request('min_price') || request('max_price'))
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-neutral-800 text-neutral-300 border border-neutral-700">
                            Price: ${{ request('min_price', '0') }} - ${{ request('max_price', '∞') }}
                            <a href="{{ route('store.index', array_merge(request()->except(['min_price', 'max_price']), [])) }}" class="ml-2 text-neutral-500 hover:text-white">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </a>
                        </span>
                    @endif
                </div>
            @endif

            <!-- Product Grid -->
            @if($products->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($products as $product)
                        <div class="bg-neutral-900 rounded-xl overflow-hidden hover:ring-1 hover:ring-neutral-700 transition group">
                            <!-- Product Image -->
                            @if($product->image_path)
                                <img src="{{ Storage::url($product->image_path) }}" 
                                     alt="{{ $product->name }}" 
                                     class="w-full h-48 object-cover">
                            @else
                                <div class="h-48 bg-gradient-to-br from-neutral-800 to-neutral-700 flex items-center justify-center">
                                    <svg class="w-16 h-16 text-neutral-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                </div>
                            @endif

                            <!-- Product Info -->
                            <div class="p-5">
                                <!-- Product Name -->
                                <h3 class="text-white font-medium text-lg mb-1 truncate" title="{{ $product->name }}">
                                    {{ $product->name }}
                                </h3>

                                <!-- Product Description -->
                                @if($product->description)
                                    <p class="text-neutral-400 text-sm mb-3 line-clamp-2">
                                        {{ Str::limit($product->description, 80) }}
                                    </p>
                                @endif

                                <!-- Price -->
                                <p class="text-emerald-400 font-semibold text-xl mb-4">
                                    ${{ number_format($product->price, 2) }}
                                </p>

                                <!-- Add to Cart Button -->
                                <form action="{{ route('cart.add', $product) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full px-4 py-2.5 bg-white text-black font-medium text-sm rounded-lg hover:bg-neutral-200 transition">
                                        Add to Cart
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($products->hasPages())
                    <div class="mt-8">
                        {{ $products->links() }}
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="bg-neutral-900 rounded-xl p-16 text-center">
                    <svg class="mx-auto h-16 w-16 text-neutral-600 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-white mb-2">No products found</h3>
                    <p class="text-neutral-400 mb-6">
                        @if(request('search') || request('min_price') || request('max_price') || request('categories'))
                            No products match your current filters.
                        @else
                            Check back soon for new products.
                        @endif
                    </p>
                    @if(request('search') || request('min_price') || request('max_price') || request('categories'))
                        <a href="{{ route('store.index') }}" class="inline-flex items-center px-4 py-2.5 bg-neutral-800 text-white font-medium text-sm rounded-lg hover:bg-neutral-700 transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Clear all filters
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</x-store-layout>
