<x-app-layout>
    <div class="mb-8">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.products.index') }}" class="text-neutral-400 hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-semibold text-white">Create Product</h1>
                <p class="text-sm text-neutral-400 mt-1">Add a new product to your catalog</p>
            </div>
        </div>
    </div>

    <div class="bg-neutral-900 rounded-xl shadow-xl p-6 sm:p-8">
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            @include('admin.products._form')

            <div class="mt-8 pt-6 border-t border-neutral-800 flex items-center justify-end gap-4">
                <a href="{{ route('admin.products.index') }}" class="px-5 py-2.5 text-sm font-medium text-neutral-400 hover:text-white transition-colors">
                    Cancel
                </a>
                <button type="submit" class="px-5 py-2.5 bg-white text-black font-medium text-sm rounded-lg hover:bg-neutral-200 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-neutral-900 transition">
                    Create Product
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
