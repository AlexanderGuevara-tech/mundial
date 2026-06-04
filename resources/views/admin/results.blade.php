@extends('layouts.app')

@section('title', 'Gestionar Resultados')

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

<div class="space-y-8">
    <h1 class="text-2xl font-bold">Gestionar Resultados</h1>

    {{-- Save results form --}}
    <form method="POST" action="{{ route('admin.results.save') }}" class="space-y-6">
        @csrf

        @forelse ($matches as $match)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 flex items-center justify-between">
                    <div class="flex-1 text-right font-medium text-lg">{{ $match->team_home }}</div>

                    <div class="flex items-center gap-3 mx-6">
                        <input
                            type="hidden"
                            name="results[{{ $loop->index }}][match_id]"
                            value="{{ $match->id }}"
                        >
                        <input
                            type="number"
                            name="results[{{ $loop->index }}][result_home]"
                            value="{{ $match->result_home }}"
                            min="0"
                            max="99"
                            placeholder="0"
                            class="w-14 h-10 text-center rounded-lg border border-gray-300 text-sm font-semibold focus:border-amber-500 focus:ring-2 focus:ring-amber-200 outline-none transition"
                        >
                        <span class="text-gray-400 font-bold">-</span>
                        <input
                            type="number"
                            name="results[{{ $loop->index }}][result_away]"
                            value="{{ $match->result_away }}"
                            min="0"
                            max="99"
                            placeholder="0"
                            class="w-14 h-10 text-center rounded-lg border border-gray-300 text-sm font-semibold focus:border-amber-500 focus:ring-2 focus:ring-amber-200 outline-none transition"
                        >
                    </div>

                    <div class="flex-1 font-medium text-lg">{{ $match->team_away }}</div>
                </div>

                <div class="px-6 py-2 bg-gray-50 text-xs text-gray-500 border-t border-gray-100">
                    {{ \Carbon\Carbon::parse($match->match_date)->format('d/m/Y') }} &middot; Grupo {{ $match->group_name }}
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 text-center text-gray-500">
                No hay partidos cargados.
            </div>
        @endforelse

        @if ($matches->isNotEmpty())
            <div class="text-center">
                <button
                    type="submit"
                    class="bg-green-600 hover:bg-green-500 text-white font-medium px-8 py-3 rounded-lg text-sm transition cursor-pointer"
                >
                    Guardar Resultados
                </button>
            </div>
        @endif
    </form>
</div>
@endsection
