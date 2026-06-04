@extends('layouts.app')

@section('title', 'Mis Pronósticos')

@section('content')
<div class="space-y-8">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold">Mis Pronósticos</h1>
        @if ($deadlinePassed)
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                Plazo vencido
            </span>
        @endif
    </div>

    @if (!$hasPaid)
        <div class="bg-amber-50 border border-amber-200 text-amber-800 rounded-xl px-6 py-4 text-sm">
            Tenés que estar habilitado (pago confirmado) para guardar predicciones.
        </div>
    @endif

    <form method="POST" action="{{ route('predictions') }}">
        @csrf

        @forelse ($groups as $groupName => $matches)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
                <div class="bg-indigo-50 px-6 py-3 border-b border-indigo-100">
                    <h2 class="text-lg font-semibold text-indigo-800">Grupo {{ $groupName }}</h2>
                </div>

                <div class="divide-y divide-gray-100">
                    @foreach ($matches as $match)
                        @php
                            $pred = $predictions[$match->id] ?? null;
                        @endphp
                        <div class="px-6 py-4 flex items-center gap-4 {{ $match->hasResult() ? 'opacity-50' : '' }}">
                            <div class="flex-1 text-right font-medium">{{ $match->team_home }}</div>

                            <div class="flex items-center gap-2">
                                @if ($deadlinePassed || $match->hasResult())
                                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-gray-100 text-sm font-semibold">
                                        {{ $pred['score_home'] ?? '?' }}
                                    </span>
                                    <span class="text-gray-400 text-xs">-</span>
                                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-gray-100 text-sm font-semibold">
                                        {{ $pred['score_away'] ?? '?' }}
                                    </span>
                                @else
                                    <input
                                        type="number"
                                        name="predictions[{{ $match->id }}][score_home]"
                                        value="{{ $pred['score_home'] ?? '' }}"
                                        min="0"
                                        max="99"
                                        placeholder="0"
                                        {{ $hasPaid ? '' : 'disabled' }}
                                        class="w-14 h-10 text-center rounded-lg border border-gray-300 text-sm font-semibold focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition"
                                    >
                                    <span class="text-gray-400 text-xs">-</span>
                                    <input
                                        type="number"
                                        name="predictions[{{ $match->id }}][score_away]"
                                        value="{{ $pred['score_away'] ?? '' }}"
                                        min="0"
                                        max="99"
                                        placeholder="0"
                                        {{ $hasPaid ? '' : 'disabled' }}
                                        class="w-14 h-10 text-center rounded-lg border border-gray-300 text-sm font-semibold focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition"
                                    >
                                @endif
                            </div>

                            <div class="flex-1 font-medium">{{ $match->team_away }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 text-center text-gray-500">
                No hay partidos cargados todavía.
            </div>
        @endforelse

        @if (!$deadlinePassed && $hasPaid && $groups->isNotEmpty())
            <div class="text-center">
                <button
                    type="submit"
                    class="bg-indigo-600 hover:bg-indigo-500 text-white font-medium px-8 py-3 rounded-lg text-sm transition cursor-pointer"
                >
                    Guardar Pronósticos
                </button>
            </div>
        @endif
    </form>
</div>
@endsection
