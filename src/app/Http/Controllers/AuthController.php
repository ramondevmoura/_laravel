<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => ['As credenciais fornecidas estão incorretas.'],
            ]);
        }

        $request->session()->regenerate();

        $user = Auth::user();

        $redirect = match ($user->role) {
            \App\Enums\UserRole::ADMIN => '/dashboard',
            \App\Enums\UserRole::PARTNER => '/dashboard',
            default => '/dashboard',
        };

        return response()->json([
            'message' => 'Login realizado com sucesso',
            'user' => $user,
            'redirect' => $redirect,
        ]);
    }

    public function user(Request $request)
    {
        return $request->user();
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Logout realizado']);
    }
}
