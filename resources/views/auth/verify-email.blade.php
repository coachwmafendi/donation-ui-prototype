@extends('layouts.auth')

@section('title', 'Verify email')

@section('content')
    <div class="sm:mx-auto sm:w-full sm:max-w-sm">
        <h2 class="text-center text-xl font-semibold leading-9 tracking-tight text-slate-900">
            Verify your email
        </h2>
        <p class="mt-2 text-center text-sm text-slate-500">
            Please verify your email address to continue
        </p>
    </div>

    <div class="mt-8">
        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 rounded-lg bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                A new verification link has been sent to your email address.
            </div>
        @endif

        <div class="text-sm text-slate-600 text-center mb-6">
            Before continuing, please check your email for a verification link. If you didn't receive the email, click below to request another.
        </div>

        <form class="space-y-4" action="{{ route('verification.send') }}" method="POST">
            @csrf

            <button
                type="submit"
                class="flex w-full justify-center rounded-lg bg-slate-900 px-3 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-slate-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-slate-900 transition"
            >
                Resend verification email
            </button>
        </form>

        <form class="mt-4" action="{{ route('logout') }}" method="POST">
            @csrf
            <button
                type="submit"
                class="flex w-full justify-center rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50 transition"
            >
                Sign out
            </button>
        </form>
    </div>
@endsection
