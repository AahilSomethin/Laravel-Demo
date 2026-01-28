<x-store-layout>
    <div class="max-w-lg mx-auto text-center py-16">
        <!-- Success Icon -->
        <div class="w-20 h-20 bg-emerald-500/10 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>

        <!-- Message -->
        <h1 class="text-3xl font-semibold text-white mb-3">Order Placed Successfully!</h1>
        <p class="text-neutral-400 mb-8">
            Thank you for your order. We'll send you an email confirmation shortly.
        </p>

        <!-- Actions -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('store.index') }}" class="inline-flex items-center justify-center px-6 py-3 bg-white text-black font-medium text-sm rounded-lg hover:bg-neutral-200 transition">
                Continue Shopping
            </a>
        </div>
    </div>
</x-store-layout>
