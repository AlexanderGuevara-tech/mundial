@extends('layouts.app')

@section('title', 'Ingresar')

@section('content')
<div class="max-w-md mx-auto mt-10">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
        <h1 class="text-2xl font-bold text-center mb-6">Ingresar</h1>

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <div>
                <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Usuario</label>
                <input
                    type="text"
                    id="username"
                    name="username"
                    value="{{ old('username') }}"
                    required
                    autofocus
                    autocomplete="username"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition"
                    placeholder="Tu usuario"
                >
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                    autocomplete="current-password"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition"
                    placeholder="••••••••"
                >
            </div>

            <button
                type="submit"
                class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-medium py-2.5 rounded-lg text-sm transition cursor-pointer"
            >
                Ingresar
            </button>
        </form>
    </div>
</div>
@endsection
