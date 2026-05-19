<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <h2 class="text-xl font-bold text-center mb-1">Welcome Back</h2>
    <p class="text-sm text-gray-500 text-center mb-6">Select your role and sign in</p>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-4">
            <x-input-label value="Login as" />
            <div class="mt-2 space-y-2">
                <label class="flex items-center gap-2 p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                    <input type="radio" name="role" value="admin" class="text-indigo-600" @checked(old('role') === 'admin') required>
                    <span><strong>Admin</strong> — manage business owners</span>
                </label>
                <label class="flex items-center gap-2 p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                    <input type="radio" name="role" value="business_owner" class="text-indigo-600" @checked(old('role') === 'business_owner')>
                    <span><strong>Business Owner</strong> — products & orders</span>
                </label>
                <label class="flex items-center gap-2 p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                    <input type="radio" name="role" value="user" class="text-indigo-600" @checked(old('role', 'user') === 'user')>
                    <span><strong>User</strong> — browse & order</span>
                </label>
            </div>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="login" value="Email or Username" />
            <x-text-input id="login" class="block mt-1 w-full" type="text" name="login" :value="old('login')" required autofocus placeholder="email or username" />
            <x-input-error :messages="$errors->get('login')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" value="Password" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600" name="remember">
                <span class="ms-2 text-sm text-gray-600">Remember me</span>
            </label>
        </div>

        <x-primary-button class="w-full justify-center mt-4">Log in</x-primary-button>
    </form>

    <div class="mt-6 text-center text-sm space-y-2">
        <p class="text-gray-600">New customer? <a href="{{ route('register') }}" class="text-indigo-600 font-medium">Register as User only</a></p>
        <p><a href="{{ route('home') }}" class="text-indigo-600 font-medium">Browse businesses without login</a></p>
    </div>
</x-guest-layout>
