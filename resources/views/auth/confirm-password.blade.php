@extends('layouts.auth')

@section('title', 'Confirm password')

@section('content')
    <div class="sm:mx-auto sm:w-full sm:max-w-sm">
        <h2 class="text-center text-xl font-semibold leading-9 tracking-tight text-slate-900">
            Confirm your password
        </h2>
        <p class="mt-2 text-center text-sm text-slate-500">
            Please confirm your password to continue
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

        <form class="space-y-5" action="{{ route('password.confirm') }}" method="POST">
            @csrf

            <div>
                <label for="password" class="block text-sm font-medium text-slate-700">
                    Password
                </label>
                <div class="mt-1.5">
                    <input
                        id="password"
                        name="password"
                        type="password"
                        autocomplete="current-password"
                        required
                        class="block w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200"
                        placeholder="••••••••"
                    >
                </div>
            </div>

            <button
                type="submit"
                class="flex w-full justify-center rounded-lg bg-slate-900 px-3 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-slate-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-slate-900 transition"
            >
                Confirm
            </button>
        </form>
    </div>
@endsection
