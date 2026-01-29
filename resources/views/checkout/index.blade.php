<x-store-layout>
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-semibold text-white">Checkout</h1>
        <p class="text-neutral-400 mt-2">Complete your order</p>
    </div>

    <form action="{{ route('checkout.store') }}" method="POST" enctype="multipart/form-data" x-data="{ paymentMethod: '{{ old('payment_method', 'cash') }}' }">
        @csrf
        <input type="hidden" name="checkout_token" value="{{ $checkoutToken }}">

        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Checkout Form -->
            <div class="flex-1">
                <div class="bg-neutral-900 rounded-xl p-6">
                    <h2 class="text-white font-semibold mb-6">Contact Information</h2>

                    <div class="space-y-5">
                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-neutral-300 mb-1.5">Full Name</label>
                            <input 
                                type="text" 
                                name="name" 
                                id="name" 
                                value="{{ old('name') }}" 
                                required
                                placeholder="John Doe"
                                class="w-full px-4 py-3 bg-neutral-800 border border-neutral-700 text-neutral-100 placeholder:text-neutral-500 rounded-lg focus:outline-none focus:ring-1 focus:ring-neutral-500 focus:border-neutral-500 transition">
                            @error('name')
                                <p class="text-red-400 text-sm mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-neutral-300 mb-1.5">Email Address</label>
                            <input 
                                type="email" 
                                name="email" 
                                id="email" 
                                value="{{ old('email') }}" 
                                required
                                placeholder="john@example.com"
                                class="w-full px-4 py-3 bg-neutral-800 border border-neutral-700 text-neutral-100 placeholder:text-neutral-500 rounded-lg focus:outline-none focus:ring-1 focus:ring-neutral-500 focus:border-neutral-500 transition">
                            @error('email')
                                <p class="text-red-400 text-sm mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-neutral-300 mb-1.5">Phone Number</label>
                            <input 
                                type="tel" 
                                name="phone" 
                                id="phone" 
                                value="{{ old('phone') }}" 
                                required
                                placeholder="+1 (555) 000-0000"
                                class="w-full px-4 py-3 bg-neutral-800 border border-neutral-700 text-neutral-100 placeholder:text-neutral-500 rounded-lg focus:outline-none focus:ring-1 focus:ring-neutral-500 focus:border-neutral-500 transition">
                            @error('phone')
                                <p class="text-red-400 text-sm mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="border-t border-neutral-800 mt-6 pt-6">
                        <h2 class="text-white font-semibold mb-6">Payment Method</h2>

                        <div class="space-y-3">
                            <!-- Cash Option -->
                            <label class="flex items-center p-4 bg-neutral-800 rounded-lg cursor-pointer border border-neutral-700 transition" :class="{ 'border-white': paymentMethod === 'cash' }">
                                <input 
                                    type="radio" 
                                    name="payment_method" 
                                    value="cash" 
                                    x-model="paymentMethod"
                                    class="w-4 h-4 bg-neutral-700 border-neutral-600 text-white focus:ring-neutral-500 focus:ring-offset-neutral-900">
                                <div class="ml-3">
                                    <span class="text-white font-medium">Cash on Delivery</span>
                                    <p class="text-sm text-neutral-400">Pay when you receive your order</p>
                                </div>
                            </label>

                            <!-- Receipt Upload Option -->
                            <label class="flex items-center p-4 bg-neutral-800 rounded-lg cursor-pointer border border-neutral-700 transition" :class="{ 'border-white': paymentMethod === 'receipt' }">
                                <input 
                                    type="radio" 
                                    name="payment_method" 
                                    value="receipt" 
                                    x-model="paymentMethod"
                                    class="w-4 h-4 bg-neutral-700 border-neutral-600 text-white focus:ring-neutral-500 focus:ring-offset-neutral-900">
                                <div class="ml-3">
                                    <span class="text-white font-medium">Upload Payment Receipt</span>
                                    <p class="text-sm text-neutral-400">Upload your payment confirmation</p>
                                </div>
                            </label>
                        </div>

                        @error('payment_method')
                            <p class="text-red-400 text-sm mt-1.5">{{ $message }}</p>
                        @enderror

                        <!-- Receipt Upload Field (shown when receipt is selected) -->
                        <div x-show="paymentMethod === 'receipt'" x-transition class="mt-5">
                            <label for="receipt" class="block text-sm font-medium text-neutral-300 mb-1.5">Upload Receipt Image</label>
                            <input 
                                type="file" 
                                name="receipt" 
                                id="receipt" 
                                accept="image/jpeg,image/jpg,image/png"
                                class="w-full px-4 py-3 bg-neutral-800 border border-neutral-700 text-neutral-100 rounded-lg focus:outline-none focus:ring-1 focus:ring-neutral-500 focus:border-neutral-500 transition file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-neutral-700 file:text-neutral-200 hover:file:bg-neutral-600">
                            <p class="text-xs text-neutral-500 mt-1.5">JPG or PNG, max 2MB</p>
                            @error('receipt')
                                <p class="text-red-400 text-sm mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Back to Cart -->
                <div class="mt-4">
                    <a href="{{ route('cart.index') }}" class="inline-flex items-center text-sm text-neutral-400 hover:text-white transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Cart
                    </a>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="lg:w-96">
                <div class="bg-neutral-900 rounded-xl p-5 lg:sticky lg:top-8">
                    <h2 class="text-white font-semibold mb-5">Order Summary</h2>

                    <div class="space-y-4 mb-5">
                        @foreach($items as $item)
                            <div class="flex gap-3">
                                @if($item['product']->image_path)
                                    <img src="{{ Storage::url($item['product']->image_path) }}" 
                                         alt="{{ $item['product']->name }}" 
                                         class="w-12 h-12 object-cover rounded-lg">
                                @else
                                    <div class="w-12 h-12 bg-gradient-to-br from-neutral-800 to-neutral-700 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-neutral-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                        </svg>
                                    </div>
                                @endif
                                <div class="flex-1 min-w-0">
                                    <p class="text-white text-sm truncate">{{ $item['product']->name }}</p>
                                    <p class="text-neutral-500 text-xs">Qty: {{ $item['quantity'] }}</p>
                                </div>
                                <p class="text-white text-sm">${{ number_format($item['subtotal'], 2) }}</p>
                            </div>
                        @endforeach
                    </div>

                    <div class="border-t border-neutral-800 pt-4 mb-5">
                        <div class="flex justify-between mb-2">
                            <span class="text-neutral-400 text-sm">Subtotal</span>
                            <span class="text-white">${{ number_format($total, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-white font-semibold">Total</span>
                            <span class="text-emerald-400 font-semibold text-xl">${{ number_format($total, 2) }}</span>
                        </div>
                    </div>

                    <button type="submit" class="w-full px-4 py-3 bg-white text-black font-medium text-sm rounded-lg hover:bg-neutral-200 transition">
                        Place Order
                    </button>
                </div>
            </div>
        </div>
    </form>
</x-store-layout>
