<x-guest-layout>
    <h1 class="text-2xl font-semibold text-white mb-2">
        Confirm password
    </h1>
    <p class="text-sm text-neutral-400 mb-8">
        This is a secure area. Please confirm your password to continue.
    </p>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <!-- Password -->
        <div>
            <input 
                id="password" 
                type="password" 
                name="password" 
                placeholder="Password"
                required 
                autocomplete="current-password"
                class="w-full px-4 py-3 bg-neutral-800 border border-neutral-700 text-neutral-100 placeholder:text-neutral-500 rounded-lg focus:outline-none focus:ring-1 focus:ring-neutral-500 focus:border-neutral-500 transition"
            >
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Button -->
        <button type="submit" class="w-full mt-6 px-4 py-3 bg-white text-black font-medium rounded-lg hover:bg-neutral-200 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-neutral-900 transition">
            {{ __('Confirm') }}
        </button>
    </form>
</x-guest-layout>
