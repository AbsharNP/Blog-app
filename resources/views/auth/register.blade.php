@extends('layouts.auth')

@section('title', 'Register')

@section('auth-title', 'Create Account')
@section('auth-subtitle', 'Join our community today')

@section('content')
@if ($errors->any())
    <div class="mb-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-600 dark:text-red-400 px-4 py-3 rounded-lg text-sm">
        <ul class="list-disc list-inside space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('create-user') }}" class="space-y-5">
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
               autofocus
               placeholder="John Doe"
               class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700
                      bg-white dark:bg-gray-800 text-gray-900 dark:text-white
                      placeholder-gray-400 dark:placeholder-gray-500
                      focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
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
                      focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
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
               placeholder="••••••••"
               class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700
                      bg-white dark:bg-gray-800 text-gray-900 dark:text-white
                      placeholder-gray-400 dark:placeholder-gray-500
                      focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
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
               placeholder="••••••••"
               class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700
                      bg-white dark:bg-gray-800 text-gray-900 dark:text-white
                      placeholder-gray-400 dark:placeholder-gray-500
                      focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
    </div>

    <!-- Terms Checkbox -->
    <div class="flex items-start">
        <input type="checkbox" 
               name="terms" 
               id="terms"
               required
               class="mt-1 w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
        <label for="terms" class="ml-2 text-sm text-gray-600 dark:text-gray-400">
            I agree to the 
            <a href="#" class="text-indigo-600 dark:text-indigo-400 hover:underline">Terms of Service</a>
            and 
            <a href="#" class="text-indigo-600 dark:text-indigo-400 hover:underline">Privacy Policy</a>
        </label>
    </div>

    <!-- Submit Button -->
    <button type="submit"
            class="w-full bg-gradient-to-r from-indigo-600 to-purple-600
                   hover:from-indigo-700 hover:to-purple-700
                   text-white font-medium py-3 rounded-lg
                   transition transform hover:scale-[1.02] active:scale-[0.98]
                   focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2
                   shadow-lg hover:shadow-xl">
        Create Account
    </button>
</form>
@endsection

@section('footer-links')
    <p>
        Already have an account?
        <a href="{{ route('login') }}" class="font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 transition">
            Sign in instead
        </a>
    </p>

    <script>
$(function () {

    $('form[action="{{ route('create-user') }}"]').on('submit', function (e) {
        e.preventDefault();

        let form = $(this);
        let submitBtn = form.find('button[type="submit"]');

        submitBtn.prop('disabled', true).text('Creating account...');

        // Remove old errors
        $('.ajax-errors').remove();

        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            success: function (res) {
                // Success message
                alert(res.message);

                // Redirect to login
                window.location.href = res.redirect;
            },
            error: function (xhr) {

                submitBtn.prop('disabled', false).text('Create Account');

                // Validation errors (422)
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    let errorHtml = '<div class="ajax-errors mb-4 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg text-sm"><ul>';

                    $.each(errors, function (key, value) {
                        errorHtml += `<li>${value[0]}</li>`;
                    });

                    errorHtml += '</ul></div>';

                    form.prepend(errorHtml);
                } else {
                    alert('Something went wrong. Please try again.');
                }
            }
        });
    });

});
</script>

@endsection