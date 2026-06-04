<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function loginForm(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse|JsonResponse
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Usuario o contraseña incorrectos.'], 401);
            }

            return back()->withErrors(['username' => 'Usuario o contraseña incorrectos.'])->onlyInput('username');
        }

        $request->session()->regenerate();

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'user' => $request->user()->only(['id', 'username', 'role', 'has_paid'])]);
        }

        return redirect()->intended(route('groups'));
    }

    public function logout(Request $request): RedirectResponse|JsonResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('login');
    }

    public function me(Request $request): JsonResponse
    {
        if (! $request->user()) {
            return response()->json(['authenticated' => false]);
        }

        return response()->json(['authenticated' => true, 'user' => $request->user()->only(['id', 'username', 'role', 'has_paid'])]);
    }

    public function passwordForm(): View
    {
        return view('auth.password');
    }

    public function updatePassword(Request $request): RedirectResponse|JsonResponse
    {
        $data = $request->validate([
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'string', 'min:4', 'confirmed'],
        ]);

        $user = $request->user();

        if (! Hash::check($data['current_password'], $user->password)) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'La contraseña actual no es correcta.'], 422);
            }

            return back()->withErrors(['current_password' => 'La contraseña actual no es correcta.']);
        }

        $user->update(['password' => $data['new_password']]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('password.form')->with('success', 'Contraseña actualizada correctamente.');
    }

    public function registerForm(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse|JsonResponse
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

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'user' => $user->only(['id', 'username', 'role', 'has_paid'])]);
        }

        return redirect()->route('admin.users')->with('success', "Usuario {$user->username} registrado.");
    }
}
