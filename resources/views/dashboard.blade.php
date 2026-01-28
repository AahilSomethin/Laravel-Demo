<x-app-layout>
    <div class="mb-8">
        <h1 class="text-2xl font-semibold text-white">Dashboard</h1>
        <p class="text-sm text-neutral-400 mt-1">Welcome back to your admin panel</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Stats Card -->
        <div class="bg-neutral-900 rounded-xl shadow-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-neutral-400">Total Products</p>
                    <p class="text-2xl font-semibold text-white mt-1">{{ \App\Models\Product::count() }}</p>
                </div>
                <div class="w-12 h-12 bg-neutral-800 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Stats Card -->
        <div class="bg-neutral-900 rounded-xl shadow-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-neutral-400">Active Products</p>
                    <p class="text-2xl font-semibold text-white mt-1">{{ \App\Models\Product::where('is_active', true)->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-neutral-800 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Stats Card -->
        <div class="bg-neutral-900 rounded-xl shadow-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-neutral-400">Total Users</p>
                    <p class="text-2xl font-semibold text-white mt-1">{{ \App\Models\User::count() }}</p>
                </div>
                <div class="w-12 h-12 bg-neutral-800 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-neutral-900 rounded-xl shadow-xl p-6">
        <h2 class="text-lg font-semibold text-white mb-4">Quick Actions</h2>
        <div class="flex flex-wrap gap-4">
            <a href="{{ route('admin.products.create') }}" class="inline-flex items-center px-4 py-2.5 bg-white text-black font-medium text-sm rounded-lg hover:bg-neutral-200 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Product
            </a>
            <a href="{{ route('admin.products.index') }}" class="inline-flex items-center px-4 py-2.5 bg-neutral-800 text-white font-medium text-sm rounded-lg hover:bg-neutral-700 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                </svg>
                View Products
            </a>
            <a href="{{ route('profile.edit') }}" class="inline-flex items-center px-4 py-2.5 bg-neutral-800 text-white font-medium text-sm rounded-lg hover:bg-neutral-700 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Edit Profile
            </a>
        </div>
    </div>
</x-app-layout>
