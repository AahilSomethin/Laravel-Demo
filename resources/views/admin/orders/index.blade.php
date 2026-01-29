<x-app-layout>
    <div x-data="orderModal()">
        <!-- Page Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-2xl font-semibold text-white">Orders</h1>
                <p class="text-sm text-neutral-400 mt-1">View and manage customer orders</p>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="bg-neutral-900 rounded-xl overflow-hidden">
            @if($orders->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-neutral-800">
                                <th class="px-6 py-4 text-left text-xs font-semibold text-neutral-400 uppercase tracking-wider">Order ID</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-neutral-400 uppercase tracking-wider">Customer</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-neutral-400 uppercase tracking-wider">Total</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-neutral-400 uppercase tracking-wider">Payment</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-neutral-400 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-4 text-right text-xs font-semibold text-neutral-400 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-800">
                            @foreach($orders as $order)
                                <tr class="hover:bg-neutral-800/50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-white font-medium">#{{ $order->id }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div>
                                            <p class="text-white">{{ $order->name }}</p>
                                            <p class="text-neutral-500 text-sm">{{ $order->email }}</p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-emerald-400 font-semibold">${{ number_format($order->total_amount, 2) }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($order->payment_method === 'cash')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-neutral-800 text-neutral-300 border border-neutral-700">
                                                Cash
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                                Receipt
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-neutral-400 text-sm">
                                        {{ $order->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <button 
                                            @click="openModal({{ $order->id }})" 
                                            class="px-3 py-1.5 bg-neutral-800 text-white text-sm font-medium rounded-lg hover:bg-neutral-700 transition">
                                            View Details
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($orders->hasPages())
                    <div class="px-6 py-4 border-t border-neutral-800">
                        {{ $orders->links() }}
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="p-16 text-center">
                    <svg class="mx-auto h-16 w-16 text-neutral-600 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-white mb-2">No orders yet</h3>
                    <p class="text-neutral-400">Orders will appear here once customers start placing them.</p>
                </div>
            @endif
        </div>

        <!-- Order Detail Modal -->
        <div 
            x-show="isOpen" 
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 overflow-y-auto" 
            style="display: none;">
            
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-black/70" @click="closeModal()"></div>

            <!-- Modal Content -->
            <div class="flex min-h-full items-center justify-center p-4">
                <div 
                    x-show="isOpen"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="relative bg-neutral-900 rounded-xl w-full max-w-lg shadow-xl border border-neutral-800"
                    @click.stop>
                    
                    <!-- Loading State -->
                    <div x-show="loading" class="p-8 text-center">
                        <svg class="animate-spin h-8 w-8 text-white mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <p class="text-neutral-400 mt-4">Loading order details...</p>
                    </div>

                    <!-- Order Details -->
                    <div x-show="!loading && order" class="p-6">
                        <!-- Header -->
                        <div class="flex justify-between items-start mb-6">
                            <div>
                                <h2 class="text-xl font-semibold text-white">Order #<span x-text="order?.id"></span></h2>
                                <p class="text-neutral-400 text-sm" x-text="order?.created_at"></p>
                            </div>
                            <button @click="closeModal()" class="p-2 text-neutral-500 hover:text-white transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <!-- Customer Info -->
                        <div class="bg-neutral-800 rounded-lg p-4 mb-5">
                            <h3 class="text-sm font-semibold text-neutral-400 uppercase tracking-wider mb-3">Customer Information</h3>
                            <div class="space-y-2">
                                <p class="text-white"><span class="text-neutral-500">Name:</span> <span x-text="order?.name"></span></p>
                                <p class="text-white"><span class="text-neutral-500">Email:</span> <span x-text="order?.email"></span></p>
                                <p class="text-white"><span class="text-neutral-500">Phone:</span> <span x-text="order?.phone"></span></p>
                            </div>
                        </div>

                        <!-- Order Items -->
                        <div class="mb-5">
                            <h3 class="text-sm font-semibold text-neutral-400 uppercase tracking-wider mb-3">Order Items</h3>
                            <div class="space-y-3">
                                <template x-for="item in order?.items" :key="item.product_name">
                                    <div class="flex justify-between items-center py-2 border-b border-neutral-800">
                                        <div>
                                            <p class="text-white" x-text="item.product_name"></p>
                                            <p class="text-neutral-500 text-sm">
                                                <span x-text="item.quantity"></span> x $<span x-text="parseFloat(item.price).toFixed(2)"></span>
                                            </p>
                                        </div>
                                        <p class="text-white font-medium">$<span x-text="parseFloat(item.subtotal).toFixed(2)"></span></p>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Total -->
                        <div class="flex justify-between items-center py-3 border-t border-neutral-700">
                            <span class="text-white font-semibold">Total</span>
                            <span class="text-emerald-400 font-semibold text-xl">$<span x-text="order?.total_amount ? parseFloat(order.total_amount).toFixed(2) : '0.00'"></span></span>
                        </div>

                        <!-- Payment Method -->
                        <div class="mt-5 pt-5 border-t border-neutral-800">
                            <h3 class="text-sm font-semibold text-neutral-400 uppercase tracking-wider mb-3">Payment</h3>
                            
                            <template x-if="order?.payment_method === 'cash'">
                                <div class="flex items-center gap-3 p-4 bg-neutral-800 rounded-lg">
                                    <div class="w-10 h-10 bg-neutral-700 rounded-full flex items-center justify-center">
                                        <svg class="w-5 h-5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-white font-medium">Cash on Delivery</p>
                                        <p class="text-neutral-500 text-sm">Payment to be collected upon delivery</p>
                                    </div>
                                </div>
                            </template>

                            <template x-if="order?.payment_method === 'receipt'">
                                <div>
                                    <div class="flex items-center gap-3 p-4 bg-neutral-800 rounded-lg mb-3">
                                        <div class="w-10 h-10 bg-emerald-500/10 rounded-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-white font-medium">Receipt Uploaded</p>
                                            <p class="text-neutral-500 text-sm">Customer uploaded payment confirmation</p>
                                        </div>
                                    </div>
                                    <template x-if="order?.receipt_url">
                                        <a :href="order.receipt_url" target="_blank" class="block">
                                            <img :src="order.receipt_url" alt="Payment Receipt" class="w-full rounded-lg border border-neutral-700 hover:border-neutral-500 transition">
                                        </a>
                                    </template>
                                </div>
                            </template>
                        </div>

                        <!-- Actions -->
                        <div class="mt-6 flex gap-3">
                            <a :href="order?.invoice_url" class="flex-1 inline-flex items-center justify-center px-4 py-2.5 bg-neutral-800 text-white font-medium text-sm rounded-lg hover:bg-neutral-700 transition border border-neutral-700">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Download Invoice
                            </a>
                            <button @click="closeModal()" class="flex-1 px-4 py-2.5 bg-neutral-800 text-white font-medium text-sm rounded-lg hover:bg-neutral-700 transition">
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function orderModal() {
            return {
                isOpen: false,
                loading: false,
                order: null,

                async openModal(orderId) {
                    this.isOpen = true;
                    this.loading = true;
                    this.order = null;

                    try {
                        const response = await fetch(`/admin/orders/${orderId}`);
                        this.order = await response.json();
                    } catch (error) {
                        console.error('Failed to load order:', error);
                    } finally {
                        this.loading = false;
                    }
                },

                closeModal() {
                    this.isOpen = false;
                    this.order = null;
                }
            }
        }
    </script>
</x-app-layout>
