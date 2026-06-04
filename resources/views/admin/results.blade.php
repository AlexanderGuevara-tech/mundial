@extends('layouts.app')

@section('title', 'Gestionar Resultados')

@section('content')
<div class="space-y-8">
    <h1 class="text-2xl font-bold">Gestionar Resultados</h1>

    {{-- Calculate scores --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex items-center justify-between">
        <div>
            <h2 class="font-semibold text-gray-900">Calcular Puntajes</h2>
            <p class="text-sm text-gray-500 mt-1">Recalcula los puntos de todos los usuarios según los resultados guardados.</p>
        </div>
        <form method="POST" action="{{ route('admin.calculate') }}">
            @csrf
            <button
                type="submit"
                class="bg-amber-500 hover:bg-amber-400 text-white font-medium px-6 py-2 rounded-lg text-sm transition cursor-pointer"
            >
                Calcular
            </button>
        </form>
    </div>

    {{-- Results detail link --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex items-center justify-between">
        <div>
            <h2 class="font-semibold text-gray-900">Detalle de Resultados</h2>
            <p class="text-sm text-gray-500 mt-1">Vista completa de todos los pronósticos vs resultados.</p>
        </div>
        <a
            href="{{ route('results') }}"
            class="bg-indigo-600 hover:bg-indigo-500 text-white font-medium px-6 py-2 rounded-lg text-sm transition inline-block text-center"
        >
            Ver detalle
        </a>
    </div>

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
