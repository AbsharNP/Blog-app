@extends('layouts.auth')

@section('title', 'Register')

@section('auth-title', 'Create Account')
@section('auth-subtitle', 'Join our community today')

@section('content')
<!-- Success/Error Messages -->
<div id="form-messages" class="mb-4 hidden"></div>

<form id="register-form" method="POST" action="{{ route('create-user') }}" class="space-y-5">
    @csrf

    <!-- Name -->
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Full Name
        </label>
        <input type="text" 
               id="name" 
               name="name" 
               value="{{ old('name') }}"
               required 
               minlength="3"
               maxlength="15"
               pattern="[a-zA-Z0-9._]+"
               autofocus
               placeholder="John Doe"
               class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700
                      bg-white dark:bg-gray-800 text-gray-900 dark:text-white
                      placeholder-gray-400 dark:placeholder-gray-500
                      focus:ring-2 focus:ring-sky-500 focus:border-transparent transition
                      error:border-red-500">
        <span id="name-error" class="mt-1 text-sm text-red-600 dark:text-red-400 hidden"></span>
    </div>

    <!-- Email -->
    <div>
        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Email Address
        </label>
        <input type="email" 
               id="email" 
               name="email" 
               value="{{ old('email') }}"
               required
               placeholder="you@example.com"
               class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700
                      bg-white dark:bg-gray-800 text-gray-900 dark:text-white
                      placeholder-gray-400 dark:placeholder-gray-500
                      focus:ring-2 focus:ring-sky-500 focus:border-transparent transition
                      error:border-red-500">
        <span id="email-error" class="mt-1 text-sm text-red-600 dark:text-red-400 hidden"></span>
    </div>

    <!-- Password -->
    <div>
        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Password
        </label>
        <input type="password" 
               id="password" 
               name="password" 
               required
               minlength="4"
               placeholder="••••••••"
               class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700
                      bg-white dark:bg-gray-800 text-gray-900 dark:text-white
                      placeholder-gray-400 dark:placeholder-gray-500
                      focus:ring-2 focus:ring-sky-500 focus:border-transparent transition
                      error:border-red-500">
        <span id="password-error" class="mt-1 text-sm text-red-600 dark:text-red-400 hidden"></span>
    </div>

    <!-- Confirm Password -->
    <div>
        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Confirm Password
        </label>
        <input type="password" 
               id="password_confirmation" 
               name="password_confirmation" 
               required
               minlength="4"
               placeholder="••••••••"
               class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700
                      bg-white dark:bg-gray-800 text-gray-900 dark:text-white
                      placeholder-gray-400 dark:placeholder-gray-500
                      focus:ring-2 focus:ring-sky-500 focus:border-transparent transition
                      error:border-red-500">
        <span id="password_confirmation-error" class="mt-1 text-sm text-red-600 dark:text-red-400 hidden"></span>
    </div>

    <!-- Terms Checkbox -->
    <div>
        <div class="flex items-start">
            <input type="checkbox" 
                   name="terms" 
                   id="terms"
                   required
                   class="mt-1 w-4 h-4 text-sky-600 border-gray-300 rounded focus:ring-sky-500">
            <label for="terms" class="ml-2 text-sm text-gray-600 dark:text-gray-400">
                I agree to the 
                <a href="#" class="text-sky-600 dark:text-sky-400 hover:underline">Terms of Service</a>
                and 
                <a href="#" class="text-sky-600 dark:text-sky-400 hover:underline">Privacy Policy</a>
            </label>
        </div>
        <span id="terms-error" class="mt-1 text-sm text-red-600 dark:text-red-400 hidden block"></span>
    </div>

    <!-- Submit Button -->
    <button type="submit"
            id="submit-btn"
            class="w-full bg-brand
                   text-white font-semibold py-3 rounded-full
                   transition transform hover:scale-[1.02] active:scale-[0.98]
                   focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2
                   shadow-lg shadow-sky-500/30 hover:shadow-xl
                   disabled:opacity-50 disabled:cursor-not-allowed">
        Create Account
    </button>
</form>
@endsection

@section('footer-links')
    <p>
        Already have an account?
        <a href="{{ route('login') }}" class="font-medium text-sky-600 dark:text-sky-400 hover:text-sky-700 dark:hover:text-sky-300 transition">
            Sign in instead
        </a>
    </p>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.js"
        integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
        crossorigin="anonymous"></script>

<script>
$(function () {
    const form = $('#register-form');
    const submitBtn = $('#submit-btn');
    const messagesDiv = $('#form-messages');

    // Clear errors on input
    form.find('input, checkbox').on('input change', function() {
        const fieldName = $(this).attr('name');
        $(`#${fieldName}-error`).addClass('hidden').text('');
        $(this).removeClass('border-red-500');
    });

    // Frontend validation
    function validateForm() {
        let isValid = true;
        const errors = {};

        // Name validation
        const name = $('#name').val().trim();
        if (!name) {
            errors.name = 'Name is required';
            isValid = false;
        } else if (name.length < 3) {
            errors.name = 'Name must be at least 3 characters';
            isValid = false;
        } else if (name.length > 15) {
            errors.name = 'Name must not exceed 15 characters';
            isValid = false;
        } else if (!/^[a-zA-Z0-9._]+$/.test(name)) {
            errors.name = 'Name can only contain letters, numbers, dots, and underscores';
            isValid = false;
        }

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
        } else if (password.length < 4) {
            errors.password = 'Password must be at least 4 characters';
            isValid = false;
        }

        // Confirm password validation
        const passwordConfirmation = $('#password_confirmation').val();
        if (!passwordConfirmation) {
            errors.password_confirmation = 'Please confirm your password';
            isValid = false;
        } else if (password !== passwordConfirmation) {
            errors.password_confirmation = 'Passwords do not match';
            isValid = false;
        }

        // Terms validation
        if (!$('#terms').is(':checked')) {
            errors.terms = 'You must agree to the terms and conditions';
            isValid = false;
        }

        // Display errors
        Object.keys(errors).forEach(field => {
            const errorSpan = $(`#${field}-error`);
            const input = $(`[name="${field}"]`);
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

        submitBtn.prop('disabled', true).text('Creating account...');

        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            success: function (res) {
                showMessage(res.message || 'Account created successfully! Redirecting...', 'success');
                
                // Redirect after short delay
                setTimeout(function() {
                    window.location.href = res.redirect || '{{ route("login") }}';
                }, 1500);
            },
            error: function (xhr) {
                submitBtn.prop('disabled', false).text('Create Account');

                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors || {};
                    
                    // Clear all errors first
                    $('[id$="-error"]').addClass('hidden').text('');
                    form.find('input').removeClass('border-red-500');

                    // Display validation errors
                    Object.keys(errors).forEach(field => {
                        const errorSpan = $(`#${field}-error`);
                        const input = $(`[name="${field}"]`);
                        if (errorSpan.length) {
                            errorSpan.removeClass('hidden').text(errors[field][0]);
                            input.addClass('border-red-500');
                        }
                    });

                    showMessage('Please fix the validation errors', 'error');
                } else {
                    showMessage(xhr.responseJSON?.message || 'Something went wrong. Please try again.', 'error');
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