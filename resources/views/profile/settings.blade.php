@extends('components.layouts.admin')

@section('title', 'Settings')

@section('content')
<div class="max-w-3xl mx-auto space-y-8">
    
    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Settings</h1>
        <p class="mt-1 text-sm text-slate-500">Manage your profile and security preferences</p>
    </div>
    
    @if (session('status'))
        <div class="rounded-lg bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm text-emerald-700">
            {{ session('status') }}
        </div>
    @endif

    {{-- Profile Section --}}
    <section class="rounded-xl border border-slate-200 bg-white overflow-hidden">
        <div class="border-b border-slate-200 px-6 py-5">
            <h2 class="text-lg font-semibold text-slate-900">Profile information</h2>
            <p class="mt-1 text-sm text-slate-500">Update your account's profile and email address.</p>
        </div>
        
        <form class="px-6 py-6 space-y-5" action="{{ route('settings.profile') }}" method="POST">
            @csrf
            @method('PUT')
            
            <div>
                <label for="name" class="block text-sm font-medium text-slate-700">Name</label>
                <input
                    id="name"
                    name="name"
                    type="text"
                    value="{{ old('name', $user->name) }}"
                    required
                    class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200"
                >
                @error('name', 'updateProfile')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="email" class="block text-sm font-medium text-slate-700">Email</label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    value="{{ old('email', $user->email) }}"
                    required
                    class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200"
                >
                @error('email', 'updateProfile')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="flex justify-end">
                <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800 transition">
                    Save changes
                </button>
            </div>
        </form>
    </section>

    {{-- Password Section --}}
    <section class="rounded-xl border border-slate-200 bg-white overflow-hidden">
        <div class="border-b border-slate-200 px-6 py-5">
            <h2 class="text-lg font-semibold text-slate-900">Update password</h2>
            <p class="mt-1 text-sm text-slate-500">Ensure your account is using a long, random password to stay secure.</p>
        </div>
        
        <form class="px-6 py-6 space-y-5" action="{{ route('settings.password') }}" method="POST">
            @csrf
            @method('PUT')
            
            <div>
                <label for="current_password" class="block text-sm font-medium text-slate-700">Current password</label>
                <input
                    id="current_password"
                    name="current_password"
                    type="password"
                    required
                    class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200"
                >
                @error('current_password', 'updatePassword')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="password" class="block text-sm font-medium text-slate-700">New password</label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    required
                    class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200"
                >
                @error('password', 'updatePassword')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-slate-700">Confirm password</label>
                <input
                    id="password_confirmation"
                    name="password_confirmation"
                    type="password"
                    required
                    class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200"
                >
            </div>
            
            <div class="flex justify-end">
                <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800 transition">
                    Update password
                </button>
            </div>
        </form>
    </section>

    {{-- Two-Factor Section --}}
    <section class="rounded-xl border border-slate-200 bg-white overflow-hidden">
        <div class="border-b border-slate-200 px-6 py-5">
            <h2 class="text-lg font-semibold text-slate-900">Two-factor authentication</h2>
            <p class="mt-1 text-sm text-slate-500">Add additional security to your account using TOTP authentication.</p>
        </div>
        
        <div class="px-6 py-6 space-y-6">
            @if ($user->two_factor_confirmed_at)
                {{-- 2FA Enabled State --}}
                <div class="flex items-center gap-3 rounded-lg bg-emerald-50 border border-emerald-200 px-4 py-3">
                    <x-icon name="check" class="size-5 text-emerald-600" />
                    <div>
                        <p class="text-sm font-medium text-emerald-800">Two-factor authentication is enabled</p>
                        <p class="text-xs text-emerald-600">Your account is more secure.</p>
                    </div>
                </div>

                {{-- Recovery Codes --}}
                @if ($twoFactorRecoveryCodes)
                    <div>
                        <h3 class="text-sm font-medium text-slate-900 mb-2">Recovery codes</h3>
                        <p class="text-sm text-slate-500 mb-3">Save these codes in a secure location. They can be used to recover access to your account if you lose your authenticator device.</p>
                        <div class="bg-slate-50 rounded-lg p-4 font-mono text-sm text-slate-700 space-y-1">
                            @foreach ($twoFactorRecoveryCodes as $code)
                                <div>{{ $code }}</div>
                            @endforeach
                        </div>
                        <form action="{{ route('settings.two-factor.recovery-codes') }}" method="POST" class="mt-3">
                            @csrf
                            <button type="submit" class="text-sm font-medium text-blue-600 hover:text-blue-500">
                                Regenerate recovery codes
                            </button>
                        </form>
                    </div>
                @endif

                <form action="{{ route('settings.two-factor.disable') }}" method="POST" class="pt-4 border-t border-slate-200">
                    @csrf
                    @method('DELETE')
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-medium text-slate-900">Disable 2FA</h3>
                            <p class="text-sm text-slate-500">This will remove the extra layer of security from your account.</p>
                        </div>
                        <button type="submit" class="rounded-lg border border-red-300 bg-white px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50 transition">
                            Disable
                        </button>
                    </div>
                </form>
            @elseif ($user->two_factor_secret)
                {{-- 2FA Setup State (not confirmed yet) --}}
                <div>
                    <p class="text-sm text-slate-700 mb-4">Scan the QR code below with your authenticator app (e.g., Google Authenticator, Authy).</p>
                    
                    <div class="flex justify-center mb-4">
                        {!! $user->twoFactorQrCodeSvg() !!}
                    </div>
                    
                    <div class="bg-slate-50 rounded-lg p-4 mb-4">
                        <p class="text-xs font-medium text-slate-500 uppercase mb-2">Setup key</p>
                        <p class="font-mono text-sm text-slate-700">{{ decrypt($user->two_factor_secret) }}</p>
                    </div>
                    
                    <form action="{{ route('settings.two-factor.confirm') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label for="code" class="block text-sm font-medium text-slate-700">Verification code</label>
                            <input
                                id="code"
                                name="code"
                                type="text"
                                inputmode="numeric"
                                maxlength="6"
                                required
                                class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 text-center tracking-widest font-mono focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200"
                                placeholder="000000"
                            >
                            @error('code', 'confirmTwoFactor')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit" class="w-full rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800 transition">
                            Confirm and enable
                        </button>
                    </form>
                </div>
            @else
                {{-- 2FA Disabled State --}}
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium text-slate-900">Authenticator app</h3>
                        <p class="text-sm text-slate-500">Use a TOTP authenticator app to add an extra layer of security.</p>
                    </div>
                    <form action="{{ route('settings.two-factor.enable') }}" method="POST">
                        @csrf
                        <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800 transition">
                            Enable
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </section>
    
</div>
@endsection
