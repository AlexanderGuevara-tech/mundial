@extends('layouts.app')

@section('title', 'Partidos')

@section('content')
<div class="space-y-8">
    <h1 class="text-2xl font-bold">Partidos del Mundial</h1>

    @forelse ($groups as $groupName => $matches)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-indigo-50 px-6 py-3 border-b border-indigo-100">
                <h2 class="text-lg font-semibold text-indigo-800">Grupo {{ $groupName }}</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 text-gray-600 uppercase text-xs tracking-wider">
                            <th class="text-left px-6 py-3">Local</th>
                            <th class="text-center px-4 py-3">vs</th>
                            <th class="text-left px-6 py-3">Visitante</th>
                            <th class="text-left px-6 py-3">Fecha</th>
                            <th class="text-center px-6 py-3">Resultado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($matches as $match)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-3 font-medium">{{ $match->team_home }}</td>
                                <td class="px-4 py-3 text-center text-gray-400 font-medium">vs</td>
                                <td class="px-6 py-3 font-medium">{{ $match->team_away }}</td>
                                <td class="px-6 py-3 text-gray-500">
                                    {{ \Carbon\Carbon::parse($match->match_date)->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-3 text-center">
                                    @if ($match->hasResult())
                                        <span class="font-semibold text-indigo-700">
                                            {{ $match->result_home }} - {{ $match->result_away }}
                                        </span>
                                    @else
                                        <span class="text-gray-400 text-xs">—</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @empty
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 text-center text-gray-500">
            No hay partidos cargados todavía.
        </div>
    @endforelse
</div>
@endsection
