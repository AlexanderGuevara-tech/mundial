@extends('layouts.app')

@section('title', 'Cambiar Contraseña')

@section('content')
<div class="max-w-md mx-auto mt-10">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
        <h1 class="text-2xl font-bold text-center mb-6">Cambiar Contraseña</h1>

        <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Contraseña actual</label>
                <input
                    type="password"
                    id="current_password"
                    name="current_password"
                    required
                    autocomplete="current-password"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition"
                    placeholder="••••••••"
                >
            </div>

            <div>
                <label for="new_password" class="block text-sm font-medium text-gray-700 mb-1">Nueva contraseña</label>
                <input
                    type="password"
                    id="new_password"
                    name="new_password"
                    required
                    minlength="4"
                    autocomplete="new-password"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition"
                    placeholder="••••••••"
                >
            </div>

            <div>
                <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirmar nueva contraseña</label>
                <input
                    type="password"
                    id="new_password_confirmation"
                    name="new_password_confirmation"
                    required
                    autocomplete="new-password"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition"
                    placeholder="••••••••"
                >
            </div>

            <button
                type="submit"
                class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-medium py-2.5 rounded-lg text-sm transition cursor-pointer"
            >
                Actualizar Contraseña
            </button>
        </form>
    </div>
</div>
@endsection
