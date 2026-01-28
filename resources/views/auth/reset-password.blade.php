<x-guest-layout>
    <h1 class="text-2xl font-semibold text-white mb-2">
        Reset password
    </h1>
    <p class="text-sm text-neutral-400 mb-8">
        Enter your new password below.
    </p>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <input 
                id="email" 
                type="email" 
                name="email" 
                value="{{ old('email', $request->email) }}" 
                placeholder="Email address"
                required 
                autofocus 
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
                placeholder="New password"
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
                placeholder="Confirm new password"
                required 
                autocomplete="new-password"
                class="w-full px-4 py-3 bg-neutral-800 border border-neutral-700 text-neutral-100 placeholder:text-neutral-500 rounded-lg focus:outline-none focus:ring-1 focus:ring-neutral-500 focus:border-neutral-500 transition"
            >
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Submit Button -->
        <button type="submit" class="w-full mt-6 px-4 py-3 bg-white text-black font-medium rounded-lg hover:bg-neutral-200 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-neutral-900 transition">
            {{ __('Reset password') }}
        </button>
    </form>
</x-guest-layout>
