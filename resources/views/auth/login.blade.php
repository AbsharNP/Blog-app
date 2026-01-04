@extends('layouts.auth')

@section('title', 'Login')
<div class="mt-12">
@section('auth-title', 'Welcome Back')
@section('auth-subtitle', 'Login to your account')

@section('content')
<form method="POST" action="{{ route('login') }}" class="space-y-5">
    @csrf

    <!-- Email -->
    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
            Email Address
        </label>
        <input type="email"
               name="email"
               value="{{ old('email') }}"
               required
               autofocus
               placeholder="you@example.com"
               class="mt-1 w-full rounded-lg
                      px-4 py-3 text-sm sm:text-base
                      border-gray-300 dark:border-gray-700
                      dark:bg-gray-800 dark:text-white
                      focus:ring-indigo-500 focus:border-indigo-500">

        @error('email')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Password -->
    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
            Password
        </label>
        <input type="password"
               name="password"
               required
               placeholder="••••••••"
               class="mt-1 w-full rounded-lg
                      px-4 py-3 text-sm sm:text-base
                      border-gray-300 dark:border-gray-700
                      dark:bg-gray-800 dark:text-white
                      focus:ring-indigo-500 focus:border-indigo-500">

        @error('password')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Remember + Forgot -->
    <div class="flex items-center justify-between text-sm">
        <label class="inline-flex items-center gap-2">
            <input type="checkbox"
                   name="remember"
                   class="rounded border-gray-300
                          text-indigo-600 focus:ring-indigo-500">
            <span class="text-gray-600 dark:text-gray-400">
                Remember me
            </span>
        </label>

        @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}"
               class="text-indigo-600 hover:underline">
                Forgot password?
            </a>
        @endif
    </div>

    <!-- Submit -->
    <button type="submit"
            class="w-full rounded-lg
                   bg-indigo-600 hover:bg-indigo-700
                   text-white font-medium
                   py-3 transition">
        Login
    </button>
</form>
@endsection
</div>
@section('footer-links')
<p>
    Don’t have an account?
    <a href="{{ route('register') }}"
       class="text-indigo-600 hover:underline font-medium">
        Register
    </a>
</p>
@endsection
