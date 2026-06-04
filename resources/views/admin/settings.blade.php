@extends('layouts.app')

@section('title', 'Configuración')

@section('content')
{{-- Admin navigation tabs --}}
<div class="mb-8 border-b border-gray-200">
    <nav class="flex gap-6 -mb-px">
        <a href="{{ route('admin.settings') }}"
            class="pb-3 text-sm font-medium border-b-2 {{ request()->routeIs('admin.settings') ? 'border-amber-500 text-amber-700' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
            ⚙️ Configuración
        </a>
        <a href="{{ route('admin.users') }}"
            class="pb-3 text-sm font-medium border-b-2 {{ request()->routeIs('admin.users') ? 'border-amber-500 text-amber-700' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
            👥 Usuarios
        </a>
        <a href="{{ route('admin.results') }}"
            class="pb-3 text-sm font-medium border-b-2 {{ request()->routeIs('admin.results') ? 'border-amber-500 text-amber-700' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
            🏆 Resultados
        </a>
        <a href="{{ route('admin.results.detail') }}"
            class="pb-3 text-sm font-medium border-b-2 {{ request()->routeIs('admin.results.detail') ? 'border-amber-500 text-amber-700' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
            📊 Detalle
        </a>
        <a href="{{ route('admin.logs') }}"
            class="pb-3 text-sm font-medium border-b-2 {{ request()->routeIs('admin.logs') ? 'border-amber-500 text-amber-700' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
            📋 Bitácora
        </a>
    </nav>
</div>

<div class="max-w-lg mx-auto">
    <h1 class="text-2xl font-bold mb-6">Configuración de la Quiniela</h1>

    <form method="POST" action="{{ route('admin.settings') }}" class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 space-y-5">
        @csrf

        <div>
            <label for="points_exact" class="block text-sm font-medium text-gray-700 mb-1">Puntos por resultado exacto</label>
            <input
                type="number"
                id="points_exact"
                name="points_exact"
                value="{{ $settings['points_exact'] }}"
                min="0"
                max="99"
                class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition"
            >
        </div>

        <div>
            <label for="points_result" class="block text-sm font-medium text-gray-700 mb-1">Puntos por resultado (solo ganador/empate)</label>
            <input
                type="number"
                id="points_result"
                name="points_result"
                value="{{ $settings['points_result'] }}"
                min="0"
                max="99"
                class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition"
            >
        </div>

        <div>
            <label for="deadline" class="block text-sm font-medium text-gray-700 mb-1">Fecha límite para pronósticos</label>
            <input
                type="date"
                id="deadline"
                name="deadline"
                value="{{ $settings['deadline'] }}"
                class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition"
            >
        </div>

        <button
            type="submit"
            class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-medium py-2.5 rounded-lg text-sm transition cursor-pointer"
        >
            Guardar Configuración
        </button>
    </form>
</div>
@endsection
