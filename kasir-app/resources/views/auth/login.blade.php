<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- HEADER -->
    <div class="text-center mb-6">
        <div class="w-12 h-12 mx-auto mb-3 rounded-xl bg-gradient-to-r from-blue-500 to-purple-500 flex items-center justify-center text-xl font-bold">
            M
        </div>

        <h2 class="text-2xl font-bold text-white">Welcome</h2>
        <p class="text-gray-400 text-sm">Sign in to your account</p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- EMAIL -->
        <div>
            <x-input-label for="email" :value="__('Email Address')" />

            <x-text-input id="email"
                class="block mt-1 w-full p-3 rounded-xl bg-gray-900 border border-gray-700 focus:ring-2 focus:ring-blue-500 outline-none text-white"
                type="email"
                name="email"
                :value="old('email')" required autofocus />

            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- PASSWORD -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password"
                class="block mt-1 w-full p-3 rounded-xl bg-gray-900 border border-gray-700 focus:ring-2 focus:ring-blue-500 outline-none text-white"
                type="password"
                name="password"
                required />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- REMEMBER -->
        <div class="flex items-center justify-between mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox"
                    class="rounded bg-gray-900 border-gray-700 text-blue-500 focus:ring-blue-500"
                    name="remember">
                <span class="ms-2 text-sm text-gray-400">Remember me</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-blue-400 hover:underline" href="{{ route('password.request') }}">
                    Forgot password?
                </a>
            @endif
        </div>

        <!-- BUTTON -->
        <x-primary-button class="w-full justify-center py-3 mt-6 rounded-xl bg-gradient-to-r from-blue-500 to-purple-500 hover:opacity-90 transition">
            Sign In →
        </x-primary-button>

    </form>
</x-guest-layout>