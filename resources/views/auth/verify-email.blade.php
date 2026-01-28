<x-guest-layout>
    <h1 class="text-2xl font-semibold text-white mb-2">
        Verify your email
    </h1>
    <p class="text-sm text-neutral-400 mb-8">
        Thanks for signing up! Please verify your email address by clicking the link we just sent you.
    </p>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-6 p-4 bg-neutral-800 border border-neutral-700 rounded-lg">
            <p class="text-sm text-neutral-300">
                A new verification link has been sent to your email address.
            </p>
        </div>
    @endif

    <div class="flex items-center justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="px-5 py-2.5 bg-white text-black font-medium text-sm rounded-lg hover:bg-neutral-200 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-neutral-900 transition">
                {{ __('Resend email') }}
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-sm text-neutral-400 hover:text-white transition-colors">
                {{ __('Log out') }}
            </button>
        </form>
    </div>
</x-guest-layout>
