<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function create(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|min:3|max:15|regex:/^[a-zA-Z0-9._]+$/',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:4|confirmed',
            'terms'    => 'accepted',
        ]);

        

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'terms'    => true,
        ]);

        return response()->json([
            'status'   => 'success',
            'message'  => 'Account created successfully. Please login.',
            'redirect' => route('login'),
        ], 201);
    }
}
