@extends('layouts.app')

@section('title', 'Registrar Usuario')

@section('content')
<div class="max-w-md mx-auto mt-10">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
        <h1 class="text-2xl font-bold text-center mb-2">Registrar Usuario</h1>
        <p class="text-sm text-gray-500 text-center mb-6">Creá un nuevo usuario para la quiniela</p>

        <form method="POST" action="{{ route('admin.register') }}" class="space-y-5">
            @csrf

            <div>
                <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Usuario</label>
                <input
                    type="text"
                    id="username"
                    name="username"
                    value="{{ old('username') }}"
                    required
                    minlength="3"
                    maxlength="255"
                    autofocus
                    class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition"
                    placeholder="Nombre de usuario"
                >
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                    minlength="4"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition"
                    placeholder="••••••••"
                >
            </div>

            <button
                type="submit"
                class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-medium py-2.5 rounded-lg text-sm transition cursor-pointer"
            >
                Registrar
            </button>
        </form>
    </div>
</div>
@endsection
