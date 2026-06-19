@extends('layouts.auth')

@section('title', 'Login')
@section('auth-title', 'Welcome Back')
@section('auth-subtitle', 'Login to your account')

@section('content')
<!-- Success/Error Messages -->
<div id="form-messages" class="mb-4 hidden"></div>

<form id="login-form" method="POST" action="{{ route('login.store') }}" class="space-y-5">
    @csrf

    <!-- Email -->
    <div>
        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
            Email Address
        </label>
        <input type="email"
               id="email"
               name="email"
               value="{{ old('email') }}"
               required
               autofocus
               placeholder="you@example.com"
               class="mt-1 w-full rounded-lg
                      px-4 py-3 text-sm sm:text-base
                      border border-gray-300 dark:border-gray-700
                      dark:bg-gray-800 dark:text-white
                      focus:ring-sky-500 focus:border-sky-500 transition
                      error:border-red-500">
        <span id="email-error" class="mt-1 text-sm text-red-600 dark:text-red-400 hidden"></span>
    </div>

    <!-- Password -->
    <div>
        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
            Password
        </label>
        <input type="password"
               id="password"
               name="password"
               required
               placeholder="••••••••"
               class="mt-1 w-full rounded-lg
                      px-4 py-3 text-sm sm:text-base
                      border border-gray-300 dark:border-gray-700
                      dark:bg-gray-800 dark:text-white
                      focus:ring-sky-500 focus:border-sky-500 transition
                      error:border-red-500">
        <span id="password-error" class="mt-1 text-sm text-red-600 dark:text-red-400 hidden"></span>
    </div>

    <!-- Remember + Forgot -->
    <div class="flex items-center justify-between text-sm">
        <label class="inline-flex items-center gap-2">
            <input type="checkbox"
                   name="remember"
                   id="remember"
                   class="rounded border-gray-300
                          text-sky-600 focus:ring-sky-500">
            <span class="text-gray-600 dark:text-gray-400">
                Remember me
            </span>
        </label>

        @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}"
               class="text-sky-600 dark:text-sky-400 hover:underline">
                Forgot password?
            </a>
        @endif
    </div>

    <!-- Submit -->
    <button type="submit"
            id="submit-btn"
            class="w-full rounded-full bg-brand
                   text-white font-semibold py-3
                   shadow-lg shadow-sky-500/30
                   hover:scale-[1.02] active:scale-[0.98] transition
                   disabled:opacity-50 disabled:cursor-not-allowed">
        Login
    </button>
</form>
@endsection

@section('footer-links')
<p>
    Don't have an account?
    <a href="{{ route('register') }}"
       class="text-sky-600 dark:text-sky-400 hover:underline font-medium">
        Register
    </a>
</p>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.js"
        integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
        crossorigin="anonymous"></script>

<script>
$(function () {
    const form = $('#login-form');
    const submitBtn = $('#submit-btn');
    const messagesDiv = $('#form-messages');

    // Clear errors on input
    form.find('input').on('input', function() {
        const fieldName = $(this).attr('name');
        $(`#${fieldName}-error`).addClass('hidden').text('');
        $(this).removeClass('border-red-500');
    });

    // Frontend validation
    function validateForm() {
        let isValid = true;
        const errors = {};

        // Email validation
        const email = $('#email').val().trim();
        if (!email) {
            errors.email = 'Email is required';
            isValid = false;
        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            errors.email = 'Please enter a valid email address';
            isValid = false;
        }

        // Password validation
        const password = $('#password').val();
        if (!password) {
            errors.password = 'Password is required';
            isValid = false;
        }

        // Display errors
        Object.keys(errors).forEach(field => {
            const errorSpan = $(`#${field}-error`);
            const input = $(`#${field}`);
            errorSpan.removeClass('hidden').text(errors[field]);
            input.addClass('border-red-500');
        });

        return isValid;
    }

    form.on('submit', function (e) {
        e.preventDefault();

        // Clear previous messages
        messagesDiv.addClass('hidden').html('');
        $('.error-message').removeClass('error-message');
        $('.success-message').removeClass('success-message');

        // Frontend validation
        if (!validateForm()) {
            showMessage('Please fix the errors above', 'error');
            return;
        }

        submitBtn.prop('disabled', true).text('Logging in...');

        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            success: function (res) {
                showMessage('Login successful! Redirecting...', 'success');
                
                // Redirect after short delay
                setTimeout(function() {
                    window.location.href = res.redirect || '{{ route("posts.index") }}';
                }, 500);
            },
            error: function (xhr) {
                submitBtn.prop('disabled', false).text('Login');

                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors || {};
                    
                    // Clear all errors first
                    $('[id$="-error"]').addClass('hidden').text('');
                    form.find('input').removeClass('border-red-500');

                    // Display validation errors
                    Object.keys(errors).forEach(field => {
                        const errorSpan = $(`#${field}-error`);
                        const input = $(`#${field}`);
                        if (errorSpan.length) {
                            errorSpan.removeClass('hidden').text(errors[field][0]);
                            input.addClass('border-red-500');
                        }
                    });

                    showMessage('Please fix the validation errors', 'error');
                } else {
                    const errorMessage = xhr.responseJSON?.message || 
                                        xhr.responseJSON?.errors?.email?.[0] ||
                                        'Invalid credentials. Please try again.';
                    showMessage(errorMessage, 'error');
                }
            }
        });
    });

    function showMessage(message, type) {
        const bgColor = type === 'success' 
            ? 'bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800 text-green-600 dark:text-green-400'
            : 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800 text-red-600 dark:text-red-400';
        
        messagesDiv
            .removeClass('hidden')
            .addClass(`${bgColor} border px-4 py-3 rounded-lg text-sm`)
            .html(message);
        
        // Scroll to top
        $('html, body').animate({ scrollTop: 0 }, 300);
    }
});
</script>
@endpush