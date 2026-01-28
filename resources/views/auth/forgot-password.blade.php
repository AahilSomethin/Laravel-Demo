<x-guest-layout>
    <h1 class="text-2xl font-semibold text-white mb-2">
        Forgot password?
    </h1>
    <p class="text-sm text-neutral-400 mb-8">
        No problem. Enter your email and we'll send you a reset link.
    </p>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <input 
                id="email" 
                type="email" 
                name="email" 
                value="{{ old('email') }}" 
                placeholder="Email address"
                required 
                autofocus
                class="w-full px-4 py-3 bg-neutral-800 border border-neutral-700 text-neutral-100 placeholder:text-neutral-500 rounded-lg focus:outline-none focus:ring-1 focus:ring-neutral-500 focus:border-neutral-500 transition"
            >
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Submit Button -->
        <button type="submit" class="w-full mt-6 px-4 py-3 bg-white text-black font-medium rounded-lg hover:bg-neutral-200 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-neutral-900 transition">
            {{ __('Send reset link') }}
        </button>
    </form>

    <!-- Back to Login -->
    <p class="mt-8 text-center text-sm text-neutral-400">
        Remember your password?
        <a href="{{ route('login') }}" class="text-neutral-400 hover:text-white transition-colors">Sign in</a>
    </p>
</x-guest-layout>
