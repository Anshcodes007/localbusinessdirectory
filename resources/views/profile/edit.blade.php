@extends(auth()->check() && auth()->user()->isUser() ? 'layouts.app-dashboard' : 'layouts.app')

@section('title', 'My Profile - ' . config('app.name'))

@section('content')
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-black text-slate-800 tracking-tight">My Profile</h1>
        <p class="text-slate-500 mt-1.5 text-sm">Manage your account information and security settings.</p>
    </div>

    <div class="space-y-6 max-w-2xl">
        <!-- Profile Information -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            @include('profile.partials.update-profile-information-form')
        </div>

        <!-- Update Password -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            @include('profile.partials.update-password-form')
        </div>

        <!-- Delete Account -->
        <div class="bg-white rounded-2xl border border-rose-100 shadow-sm p-6">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
@endsection
