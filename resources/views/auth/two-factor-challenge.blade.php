@extends('layouts.auth')

@section('title', 'Two-Factor Authentication')

@section('content')
    <div class="sm:mx-auto sm:w-full sm:max-w-sm">
        <h2 class="text-center text-xl font-semibold leading-9 tracking-tight text-slate-900">
            Two-factor authentication
        </h2>
        <p class="mt-2 text-center text-sm text-slate-500">
            Please confirm access to your account
        </p>
    </div>

    <div class="mt-8">
        @if ($errors->any())
            <div class="mb-4 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form class="space-y-5" action="{{ route('two-factor.login') }}" method="POST">
            @csrf

            <div>
                <label for="code" class="block text-sm font-medium text-slate-700">
                    Authentication code
                </label>
                <div class="mt-1.5">
                    <input
                        id="code"
                        name="code"
                        type="text"
                        inputmode="numeric"
                        pattern="[0-9]*"
                        maxlength="6"
                        autocomplete="one-time-code"
                        required
                        autofocus
                        class="block w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200 text-center tracking-widest font-mono"
                        placeholder="000000"
                    >
                </div>
                <p class="mt-2 text-xs text-slate-500">
                    Enter the 6-digit code from your authenticator app.
                </p>
            </div>

            <button
                type="submit"
                class="flex w-full justify-center rounded-lg bg-slate-900 px-3 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-slate-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-slate-900 transition"
            >
                Verify
            </button>
        </form>

        <form class="mt-4" action="{{ route('two-factor.login') }}" method="POST">
            @csrf

            <div>
                <label for="recovery_code" class="block text-sm font-medium text-slate-700">
                    Recovery code
                </label>
                <div class="mt-1.5">
                    <input
                        id="recovery_code"
                        name="recovery_code"
                        type="text"
                        autocomplete="off"
                        class="block w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200 font-mono"
                        placeholder="xxxx-xxxx-xxxx-xxxx"
                    >
                </div>
                <p class="mt-2 text-xs text-slate-500">
                    Use a recovery code if you can't access your authenticator app.
                </p>
            </div>

            <button
                type="submit"
                class="mt-4 flex w-full justify-center rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50 transition"
            >
                Use recovery code
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-slate-500">
            <a href="{{ route('login') }}" class="font-semibold text-blue-600 hover:text-blue-500">
                Back to login
            </a>
        </p>
    </div>
@endsection
