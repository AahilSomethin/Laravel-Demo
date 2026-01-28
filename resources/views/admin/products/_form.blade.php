<div class="space-y-6">
    <div>
        <label for="name" class="block text-sm font-medium text-neutral-300 mb-1.5">Name</label>
        <input type="text" name="name" id="name" value="{{ old('name', $product->name ?? '') }}" required
            placeholder="Product name"
            class="w-full px-4 py-3 bg-neutral-800 border border-neutral-700 text-neutral-100 placeholder:text-neutral-500 rounded-lg focus:outline-none focus:ring-1 focus:ring-neutral-500 focus:border-neutral-500 transition">
        @error('name')
            <p class="text-red-400 text-sm mt-1.5">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="description" class="block text-sm font-medium text-neutral-300 mb-1.5">Description</label>
        <textarea name="description" id="description" rows="4"
            placeholder="Enter product description..."
            class="w-full px-4 py-3 bg-neutral-800 border border-neutral-700 text-neutral-100 placeholder:text-neutral-500 rounded-lg focus:outline-none focus:ring-1 focus:ring-neutral-500 focus:border-neutral-500 transition resize-none">{{ old('description', $product->description ?? '') }}</textarea>
        @error('description')
            <p class="text-red-400 text-sm mt-1.5">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="price" class="block text-sm font-medium text-neutral-300 mb-1.5">Price</label>
        <div class="relative">
            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-neutral-500">$</span>
            <input type="number" name="price" id="price" value="{{ old('price', $product->price ?? '') }}" step="0.01" min="0" required
                placeholder="0.00"
                class="w-full pl-8 pr-4 py-3 bg-neutral-800 border border-neutral-700 text-neutral-100 placeholder:text-neutral-500 rounded-lg focus:outline-none focus:ring-1 focus:ring-neutral-500 focus:border-neutral-500 transition">
        </div>
        @error('price')
            <p class="text-red-400 text-sm mt-1.5">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex items-center">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" name="is_active" id="is_active" value="1"
            {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }}
            class="w-4 h-4 rounded bg-neutral-800 border-neutral-700 text-white focus:ring-neutral-500 focus:ring-offset-neutral-900">
        <label for="is_active" class="ml-3 block text-sm text-neutral-300">
            Active
            <span class="text-neutral-500">— Product will be visible in the catalog</span>
        </label>
        @error('is_active')
            <p class="text-red-400 text-sm mt-1.5 ml-7">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="image" class="block text-sm font-medium text-neutral-300 mb-1.5">Product Image</label>
        
        @if(isset($product) && $product->image_path)
            <div class="mb-3">
                <img src="{{ Storage::url($product->image_path) }}" 
                     alt="{{ $product->name }}" 
                     class="w-32 h-32 object-cover rounded-lg border border-neutral-700">
                <p class="text-xs text-neutral-500 mt-1">Current image</p>
            </div>
        @endif
        
        <input type="file" name="image" id="image" accept="image/jpeg,image/jpg,image/png"
            class="w-full px-4 py-3 bg-neutral-800 border border-neutral-700 text-neutral-100 rounded-lg focus:outline-none focus:ring-1 focus:ring-neutral-500 focus:border-neutral-500 transition file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-neutral-700 file:text-neutral-200 hover:file:bg-neutral-600">
        <p class="text-xs text-neutral-500 mt-1.5">Optional. JPG or PNG, max 2MB.</p>
        @error('image')
            <p class="text-red-400 text-sm mt-1.5">{{ $message }}</p>
        @enderror
    </div>
</div>
