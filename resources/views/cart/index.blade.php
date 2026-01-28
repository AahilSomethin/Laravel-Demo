<x-store-layout>
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-semibold text-white">Shopping Cart</h1>
        <p class="text-neutral-400 mt-2">Review your items before checkout</p>
    </div>

    @if(count($items) > 0)
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Cart Items -->
            <div class="flex-1">
                <div class="bg-neutral-900 rounded-xl overflow-hidden">
                    @foreach($items as $item)
                        <div class="p-5 {{ !$loop->last ? 'border-b border-neutral-800' : '' }}">
                            <div class="flex gap-4">
                                <!-- Product Image -->
                                @if($item['product']->image_path)
                                    <img src="{{ Storage::url($item['product']->image_path) }}" 
                                         alt="{{ $item['product']->name }}" 
                                         class="w-20 h-20 object-cover rounded-lg">
                                @else
                                    <div class="w-20 h-20 bg-gradient-to-br from-neutral-800 to-neutral-700 rounded-lg flex items-center justify-center">
                                        <svg class="w-8 h-8 text-neutral-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                        </svg>
                                    </div>
                                @endif

                                <!-- Product Info -->
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-white font-medium truncate">{{ $item['product']->name }}</h3>
                                    <p class="text-emerald-400 font-semibold mt-1">${{ number_format($item['product']->price, 2) }}</p>
                                </div>

                                <!-- Quantity Control -->
                                <div class="flex items-center gap-3">
                                    <form action="{{ route('cart.update', $item['product']) }}" method="POST" class="flex items-center">
                                        @csrf
                                        @method('PATCH')
                                        <input 
                                            type="number" 
                                            name="quantity" 
                                            value="{{ $item['quantity'] }}" 
                                            min="1" 
                                            max="99"
                                            class="w-16 px-2 py-1.5 bg-neutral-800 border border-neutral-700 text-neutral-100 rounded-lg text-sm text-center focus:outline-none focus:ring-1 focus:ring-neutral-500"
                                            onchange="this.form.submit()">
                                    </form>

                                    <form action="{{ route('cart.remove', $item['product']) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-neutral-500 hover:text-red-400 transition">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>

                                <!-- Subtotal -->
                                <div class="text-right min-w-[80px]">
                                    <p class="text-white font-semibold">${{ number_format($item['subtotal'], 2) }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Continue Shopping -->
                <div class="mt-4">
                    <a href="{{ route('store.index') }}" class="inline-flex items-center text-sm text-neutral-400 hover:text-white transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Continue Shopping
                    </a>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="lg:w-80">
                <div class="bg-neutral-900 rounded-xl p-5 lg:sticky lg:top-8">
                    <h2 class="text-white font-semibold mb-5">Order Summary</h2>

                    <div class="space-y-3 mb-5">
                        <div class="flex justify-between text-sm">
                            <span class="text-neutral-400">Subtotal ({{ array_sum(array_column($items, 'quantity')) }} items)</span>
                            <span class="text-white">${{ number_format($total, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-neutral-400">Shipping</span>
                            <span class="text-neutral-400">Calculated at checkout</span>
                        </div>
                    </div>

                    <div class="border-t border-neutral-800 pt-4 mb-5">
                        <div class="flex justify-between">
                            <span class="text-white font-semibold">Total</span>
                            <span class="text-emerald-400 font-semibold text-xl">${{ number_format($total, 2) }}</span>
                        </div>
                    </div>

                    <a href="{{ route('checkout.index') }}" class="block w-full px-4 py-3 bg-white text-black font-medium text-sm rounded-lg hover:bg-neutral-200 transition text-center">
                        Proceed to Checkout
                    </a>
                </div>
            </div>
        </div>
    @else
        <!-- Empty Cart State -->
        <div class="bg-neutral-900 rounded-xl p-16 text-center">
            <svg class="mx-auto h-16 w-16 text-neutral-600 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            <h3 class="text-lg font-medium text-white mb-2">Your cart is empty</h3>
            <p class="text-neutral-400 mb-6">Looks like you haven't added any products yet.</p>
            <a href="{{ route('store.index') }}" class="inline-flex items-center px-4 py-2.5 bg-white text-black font-medium text-sm rounded-lg hover:bg-neutral-200 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Start Shopping
            </a>
        </div>
    @endif
</x-store-layout>
