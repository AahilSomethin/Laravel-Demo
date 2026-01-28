<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - Store</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-neutral-950 flex flex-col">
            <!-- Navigation -->
            <nav class="bg-neutral-900 border-b border-neutral-800">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center">
                            <!-- Logo -->
                            <a href="{{ route('store.index') }}" class="flex items-center">
                                <x-application-logo class="block h-8 w-auto fill-current text-neutral-500" />
                            </a>

                            <!-- Navigation Links -->
                            <div class="hidden sm:flex sm:space-x-8 sm:ms-10">
                                <a href="{{ route('store.index') }}" class="inline-flex items-center px-1 pt-1 text-sm font-medium text-white border-b-2 border-white">
                                    Store
                                </a>
                            </div>
                        </div>

                        <!-- Right Side -->
                        <div class="flex items-center space-x-4">
                            <!-- Cart Icon -->
                            <a href="{{ route('cart.index') }}" class="relative p-2 text-neutral-400 hover:text-white transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                @php $cartCount = \App\Http\Controllers\CartController::getCartCount(); @endphp
                                @if($cartCount > 0)
                                    <span class="absolute -top-1 -right-1 bg-white text-black text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">
                                        {{ $cartCount > 99 ? '99+' : $cartCount }}
                                    </span>
                                @endif
                            </a>

                            @auth
                                <a href="{{ route('dashboard') }}" class="text-sm text-neutral-400 hover:text-white transition-colors">
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="text-sm text-neutral-400 hover:text-white transition-colors">
                                    Log in
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 bg-white text-black font-medium text-sm rounded-lg hover:bg-neutral-200 transition">
                                        Sign up
                                    </a>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <main class="flex-1 max-w-7xl w-full mx-auto py-8 px-4 sm:px-6 lg:px-8">
                @if (session('success'))
                    <div class="mb-6 p-4 bg-neutral-800 border border-neutral-700 rounded-lg">
                        <p class="text-sm text-neutral-300">{{ session('success') }}</p>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-6 p-4 bg-red-900/20 border border-red-800 rounded-lg">
                        <p class="text-sm text-red-400">{{ session('error') }}</p>
                    </div>
                @endif

                {{ $slot }}
            </main>

            <!-- Footer -->
            <footer class="border-t border-neutral-800">
                <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
                    <p class="text-center text-sm text-neutral-500">
                        &copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.
                    </p>
                </div>
            </footer>
        </div>
    </body>
</html>
