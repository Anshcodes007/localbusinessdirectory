<section class="space-y-5">
    <div class="mb-6">
        <h2 class="text-lg font-bold text-slate-800">Delete Account</h2>
        <p class="mt-1 text-sm text-slate-500">
            Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.
        </p>
    </div>

    <button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="bg-rose-50 hover:bg-rose-100 text-rose-600 hover:text-rose-700 font-bold px-6 py-2.5 rounded-xl border border-rose-200 hover:border-rose-300 transition text-sm focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-2"
    >
        Delete Account
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <div class="flex items-center gap-3 mb-5">
                <div class="w-10 h-10 bg-rose-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-base font-bold text-slate-800">Are you sure you want to delete your account?</h2>
                    <p class="text-sm text-slate-500 mt-0.5">This action cannot be undone.</p>
                </div>
            </div>

            <p class="text-sm text-slate-600 mb-5">Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm.</p>

            <div>
                <label for="delete-password" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 sr-only">Password</label>
                <input id="delete-password" name="password" type="password"
                       class="block w-3/4 bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 placeholder-slate-400 transition"
                       placeholder="Enter your password to confirm">
                @error('password', 'userDeletion')
                    <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button"
                        x-on:click="$dispatch('close')"
                        class="px-5 py-2 text-sm font-semibold text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition focus:outline-none focus:ring-2 focus:ring-slate-400">
                    Cancel
                </button>
                <button type="submit"
                        class="px-5 py-2 text-sm font-bold text-white bg-rose-600 hover:bg-rose-700 rounded-xl shadow-sm hover:shadow transition focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-2">
                    Delete Account
                </button>
            </div>
        </form>
    </x-modal>
</section>
