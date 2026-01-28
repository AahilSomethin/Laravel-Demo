<x-app-layout>
    <div class="mb-8">
        <h1 class="text-2xl font-semibold text-white">Profile</h1>
        <p class="text-sm text-neutral-400 mt-1">Manage your account settings</p>
    </div>

    <div class="space-y-6">
        <div class="bg-neutral-900 rounded-xl shadow-xl p-6 sm:p-8">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="bg-neutral-900 rounded-xl shadow-xl p-6 sm:p-8">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="bg-neutral-900 rounded-xl shadow-xl p-6 sm:p-8">
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>
