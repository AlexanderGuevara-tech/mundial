<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials)) {
            return response()->json(['error' => 'Usuario o contraseña incorrectos.'], 401);
        }

        $request->session()->regenerate();

        return response()->json(['success' => true, 'user' => $request->user()->only(['id', 'username', 'role', 'has_paid'])]);
    }

    public function logout(Request $request): JsonResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['success' => true]);
    }

    public function me(Request $request): JsonResponse
    {
        if (! $request->user()) {
            return response()->json(['authenticated' => false]);
        }

        return response()->json(['authenticated' => true, 'user' => $request->user()->only(['id', 'username', 'role', 'has_paid'])]);
    }

    public function register(Request $request): JsonResponse
    {
        $data = $request->validate([
            'username' => ['required', 'string', 'min:3', 'max:255', 'unique:users,username'],
            'password' => ['required', 'string', 'min:4'],
        ]);

        $user = User::query()->create([
            'username' => $data['username'],
            'password' => $data['password'],
            'role' => 'user',
            'has_paid' => false,
        ]);

        return response()->json(['success' => true, 'user' => $user->only(['id', 'username', 'role', 'has_paid'])]);
    }
}
