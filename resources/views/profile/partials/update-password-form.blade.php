<section>
    <div class="mb-6">
        <h2 class="text-lg font-bold text-slate-800">Update Password</h2>
        <p class="mt-1 text-sm text-slate-500">Ensure your account is using a long, random password to stay secure.</p>
    </div>

    <form method="post" action="{{ route('password.update') }}" class="space-y-5">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Current Password</label>
            <input id="update_password_current_password" name="current_password" type="password"
                   class="block w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 placeholder-slate-400 transition"
                   autocomplete="current-password"
                   placeholder="Enter current password">
            @error('current_password', 'updatePassword')
                <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="update_password_password" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">New Password</label>
            <input id="update_password_password" name="password" type="password"
                   class="block w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 placeholder-slate-400 transition"
                   autocomplete="new-password"
                   placeholder="Enter new password">
            @error('password', 'updatePassword')
                <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="update_password_password_confirmation" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Confirm Password</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password"
                   class="block w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 placeholder-slate-400 transition"
                   autocomplete="new-password"
                   placeholder="Confirm new password">
            @error('password_confirmation', 'updatePassword')
                <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-6 py-2.5 rounded-xl shadow-sm hover:shadow transition text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                Update Password
            </button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition
                   x-init="setTimeout(() => show = false, 2500)"
                   class="flex items-center gap-1.5 text-sm text-emerald-600 font-semibold">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                    Password updated!
                </p>
            @endif
        </div>
    </form>
</section>
