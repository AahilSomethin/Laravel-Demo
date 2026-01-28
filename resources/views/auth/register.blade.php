<x-guest-layout>
    <h1 class="text-2xl font-semibold text-white mb-8">
        Create your account
    </h1>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <input 
                id="name" 
                type="text" 
                name="name" 
                value="{{ old('name') }}" 
                placeholder="Full name"
                required 
                autofocus 
                autocomplete="name"
                class="w-full px-4 py-3 bg-neutral-800 border border-neutral-700 text-neutral-100 placeholder:text-neutral-500 rounded-lg focus:outline-none focus:ring-1 focus:ring-neutral-500 focus:border-neutral-500 transition"
            >
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <input 
                id="email" 
                type="email" 
                name="email" 
                value="{{ old('email') }}" 
                placeholder="Email address"
                required 
                autocomplete="username"
                class="w-full px-4 py-3 bg-neutral-800 border border-neutral-700 text-neutral-100 placeholder:text-neutral-500 rounded-lg focus:outline-none focus:ring-1 focus:ring-neutral-500 focus:border-neutral-500 transition"
            >
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <input 
                id="password" 
                type="password" 
                name="password" 
                placeholder="Password"
                required 
                autocomplete="new-password"
                class="w-full px-4 py-3 bg-neutral-800 border border-neutral-700 text-neutral-100 placeholder:text-neutral-500 rounded-lg focus:outline-none focus:ring-1 focus:ring-neutral-500 focus:border-neutral-500 transition"
            >
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <input 
                id="password_confirmation" 
                type="password" 
                name="password_confirmation" 
                placeholder="Confirm password"
                required 
                autocomplete="new-password"
                class="w-full px-4 py-3 bg-neutral-800 border border-neutral-700 text-neutral-100 placeholder:text-neutral-500 rounded-lg focus:outline-none focus:ring-1 focus:ring-neutral-500 focus:border-neutral-500 transition"
            >
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Register Button -->
        <button type="submit" class="w-full mt-6 px-4 py-3 bg-white text-black font-medium rounded-lg hover:bg-neutral-200 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-neutral-900 transition">
            {{ __('Create account') }}
        </button>
    </form>

    <!-- Sign In Link -->
    <p class="mt-8 text-center text-sm text-neutral-400">
        Already have an account?
        <a href="{{ route('login') }}" class="text-neutral-400 hover:text-white transition-colors">Sign in</a>
    </p>
</x-guest-layout>
