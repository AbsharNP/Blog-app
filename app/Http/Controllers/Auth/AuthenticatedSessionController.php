<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (! Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'The provided credentials do not match our records.',
                    'errors' => [
                        'email' => ['The provided credentials do not match our records.']
                    ]
                ], 422);
            }
            
            throw ValidationException::withMessages([
                'email' => __('The provided credentials do not match our records.'),
            ]);
        }

        $request->session()->regenerate();

        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Login successful',
                'redirect' => route('dashboard')
            ]);
        }

        return redirect()->intended(route('dashboard'));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Logged out successfully',
                'redirect' => route('home')
            ]);
        }

        return redirect('/');
    }
}